const express = require('express');
const multer = require('multer');
const path = require('path');
const fs = require('fs');

const app = express();

function getFolder(filePath) {
    if (filePath.includes('\0')) throw new Error('Invalid path !!');
    filePath = filePath.replace(/\\/g, path.sep).replace(/\//g, path.sep);
    const p = filePath.lastIndexOf(path.sep);
    if (p !== -1) {
        filePath = filePath.substring(0, p);
    }
    return filePath;
}

function guid() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        const r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    });
}

const foldername = 'imageuploads';
const storage = multer.diskStorage({
    destination: function (req, file, cb) {
        const folder = getFolder(__filename);
        const phydir = path.join(folder, foldername);
        if (!fs.existsSync(phydir)) {
            fs.mkdirSync(phydir, { recursive: true });
        }
        cb(null, phydir);
    },
    filename: function (req, file, cb) {
        cb(null, `${guid()}.jpg`);
    }
});
const upload = multer({ storage: storage });

app.post('/upload', upload.single('fileforphp'), (req, res) => {
    const filename = req.file.filename;
    res.send(`READY:${foldername}/${filename}`);
});

app.listen(3000, () => {
    console.log('Server started on port 3000');
});
