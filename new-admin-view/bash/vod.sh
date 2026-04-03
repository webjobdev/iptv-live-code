#!/usr/bin/env bash
set -e
# Usage create-vod-hls.sh SOURCE_FILE COMMA_SEPERATED_RESOLUTIONS [OUTPUT_NAME]
[[ ! "${1}" ]] && echo "Usage: ${0} SOURCE_FILE COMMA_SEPERATED_RESOLUTIONS [OUTPUT_NAME]" && exit 1
[[ ! "${2}" ]] && echo "Usage: ${0} ${1} COMMA_SEPERATED_RESOLUTIONS [OUTPUT_NAME]" && exit 1

# comment/add lines here to control which renditions would be created
renditions=(
# resolution  bitrate  audio-rate
  "426x240    400k     64k"
  "640x360    800k     96k"
  "842x480    1400k    128k"
  "1280x720   2800k    128k"
  "1920x1080  5000k    192k"
# "2560x1440  11000k   192k"
# "3840x2160  17000k   192k"
)

segment_target_duration=4       # try to create a new segment every X seconds
max_bitrate_ratio=1.07          # maximum accepted bitrate fluctuations
rate_monitor_buffer_ratio=1.5   # maximum buffer size between bitrate conformance checks
#########################################################################

source="${1}"
convert_resolution="${2}"
target="${3}"
ffmpegPath="ffmpeg"
ffProbePath="ffprobe"
if [[ ! "${target}" ]]; then
  target="${source##*/}" # leave only last component of path
  target="${target%.*}"  # strip extension
fi
mkdir -p ${target}
BASE_URL="$(pwd)"

function generateKeyForVideo() {
  encrypted_key="$(openssl rand 16)";
  echo -e ${encrypted_key} > ${target}/enc.key
}

checkCharCount=$(cat $target/enc.key| wc -c)
until [[ "$checkCharCount" == "16" ]];
  do
    generateKeyForVideo
    checkCharCount=$(cat $target/enc.key| wc -c)
  done
echo enc.key > ${target}/enc.keyinfo
echo ${target}/enc.key >> ${target}/enc.keyinfo
echo $(openssl rand -hex 16) >> ${target}/enc.keyinfo

#openssl rand 16 > ${target}/enc.key

key_frames_interval="$(echo `${ffProbePath} ${source} 2>&1 | grep -oE '[[:digit:]]+(.[[:digit:]]+)? fps' | grep -oE '[[:digit:]]+(.[[:digit:]]+)?'`*2 | bc || echo '')"
key_frames_interval=${key_frames_interval:-50}
key_frames_interval=$(echo `printf "%.1f\n" $(bc -l <<<"$key_frames_interval/10")`*10 | bc) # round
key_frames_interval=${key_frames_interval%.*} # truncate to integer
# encrypted_key=openssl rand 16
# static parameters that are similar for all renditions
static_params=" -strict -2 -c:a aac -ar 48000 -c:v h264 -profile:v main -crf 20 -sc_threshold 0"
static_params+=" -g ${key_frames_interval} -keyint_min ${key_frames_interval}"
static_params+=" -hls_list_size 0"
static_params+=" -hls_time ${segment_target_duration}"
static_params+=" -hls_key_info_file ${target}/enc.keyinfo"
#static_params+=" -hls_playlist_type vod"

# misc params
misc_params="-hide_banner -y "

master_playlist="#EXTM3U
#EXT-X-VERSION:3
"
cmd=""
for rendition in "${renditions[@]}"; do
  # drop extraneous spaces
  rendition="${rendition/[[:space:]]+/ }"

  # rendition fields
  resolution="$(echo ${rendition} | cut -d ' ' -f 1)"
  bitrate="$(echo ${rendition} | cut -d ' ' -f 2)"
  audiorate="$(echo ${rendition} | cut -d ' ' -f 3)"
  actual_resolution="$(${ffProbePath} -v error -select_streams v:0 -show_entries stream=width,height -of csv=s=x:p=0 ${source})";
  actual_width="$(echo ${actual_resolution} | grep -oE '^[[:digit:]]+')"
  actual_height="$(echo ${actual_resolution} | grep -oE '[[:digit:]]+$')"
  # calculated fields
  width="$(echo ${resolution} | grep -oE '^[[:digit:]]+')"
  height="$(echo ${resolution} | grep -oE '[[:digit:]]+$')"
  if [[ "$actual_width" -gt "$width" ]] || [[ "$actual_width" -eq "$width" ]] && [[ $convert_resolution = *"$resolution"* ]];
  then
    maxrate="$(echo "`echo ${bitrate} | grep -oE '[[:digit:]]+'`*${max_bitrate_ratio}" | bc)"
    bufsize="$(echo "`echo ${bitrate} | grep -oE '[[:digit:]]+'`*${rate_monitor_buffer_ratio}" | bc)"
    bandwidth="$(echo ${bitrate} | grep -oE '[[:digit:]]+')000"
    name="${height}p"
    
#    cmd+=" ${static_params} -vf scale=w=${width}:h=${height}:force_original_aspect_ratio=decrease"
    cmd+=" ${static_params} -vf scale=trunc(iw/2)*2:trunc(ih/2)*2"
    cmd+=" -b:v ${bitrate} -maxrate ${maxrate%.*}k -bufsize ${bufsize%.*}k -b:a ${audiorate}"
    cmd+=" -hls_segment_filename ${target}/${name}_%03d.ts ${target}/${name}.m3u8"
    
    # add rendition entry in the master playlist
    master_playlist+="#EXT-X-STREAM-INF:BANDWIDTH=${bandwidth},RESOLUTION=${resolution}\n${name}.m3u8\n"
  fi
done

# start conversion
echo -e "Executing command:\ ${ffmpegPath} ${misc_params} -i ${source} ${cmd}"
if ${ffmpegPath} ${misc_params} -i ${source} ${cmd}; then
  echo -e "${master_playlist}" > ${target}/playlist.m3u8
  rm ${target}/enc.keyinfo
else
  rm ${target}/enc.keyinfo
fi