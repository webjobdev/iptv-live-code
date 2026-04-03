<?php

namespace Contus\Subscribers\Http\Controllers\Admin;

use Barryvdh\DomPDF\Facade\Pdf;
use Contus\Base\Controller;
use Contus\Subscribers\Model\OrgSubscriberAndPayment;
use Contus\Subscribers\Model\OrgSubscribers;
use Contus\Subscribers\Model\SubscriberAssignedDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubscriberIndexController extends Controller
{

    public function index()
    {
        return view('subscribers::index');
    }

    public function subscriberGridList()
    {
        return view('subscribers::gridView');
    }

    public function postadd()
    {
        return view('subscribers::subscriber');
    }

    public function deviceIndex()
    {
        return view('subscribers::devices.index');
    }

    public function deviceGridList()
    {
        return view('subscribers::devices.gridView');
    }

    public function activationIndex()
    {
        return view('subscribers::activation.index');
    }

    public function subscriptionGridList()
    {
        return view('subscribers::activation.gridView');
    }

    public function addslot()
    {
        return view('subscribers::activation.add-slot');
    }

    public function viewslot()
    {
        return view('subscribers::activation.view-slot');
    }

    public function assignGridList()
    {
        return view('subscribers::activation.assigne-device.gridView');
    }

    public function creditcardIndex()
    {
        return view('subscribers::activation.creditcard.index');
    }

    public function creditcardGridList()
    {
        return view('subscribers::activation.creditcard.gridView');
    }

    public function paymentIndex()
    {
        return view('subscribers::activation.payment-history.index');
    }

    public function paymentGridList()
    {
        return view('subscribers::activation.payment-history.gridView');
    }

    public function partnerIndex()
    {
        return view('subscribers::activation.partner-product.index');
    }

    public function partnerGridList()
    {
        return view('subscribers::activation.partner-product.gridView');
    }

    public function notesIndex()
    {
        return view('subscribers::activation.subscriber-notes.index');
    }

    public function notesGridList()
    {
        return view('subscribers::activation.subscriber-notes.gridView');
    }

    public function customstream()
    {
        return view('subscribers::activation.custom-stream.tv-channel-list.index');
    }

    public function tvChannelGridList()
    {
        return view('subscribers::activation.custom-stream.tv-channel-list.gridView');
    }

    public function videoOnDemand()
    {
        return view('subscribers::activation.custom-stream.video-on-demand.index');
    }

    public function videoOnDemandGridList()
    {
        return view('subscribers::activation.custom-stream.video-on-demand.gridView');
    }















    /**
     * Delete an organization subscriber.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $organizationId = $request->input('id');

        if (!$organizationId) {
            return response()->json(['success' => false, 'message' => 'Organization ID is required'], 400);
        }

        $organization = OrgSubscribers::find($organizationId);
        if (!$organization) {
            return response()->json(['success' => false, 'message' => 'Organization not found'], 404);
        }

        try {
            $organization->delete();

            return response()->json(['success' => true, 'message' => 'Organization deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete organization.'], 500);
        }
    }

    /**
     * Download PDF for subscriber payment history.
     *
     * @param  mixed $data
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf($id)
    {
        // Log::info('[PDF] downloadPdf method called.', ['id' => $id]);

        if (!$id) {
            // Log::warning('[PDF] No ID provided.');
            return response()->json([
                'error' => 'true',
                'message' => 'ID not found'
            ], 400);
        }

        // Fetch main record with relations
        $record = OrgSubscriberAndPayment::where('id', $id)
            ->with('subscriber_detail', 'transaction_detail')
            ->first();

        if (!$record) {
            // Log::warning('[PDF] Record not found for given ID.', ['id' => $id]);
            return response()->json([
                'error' => 'true',
                'message' => 'Record not found.'
            ], 400);
        }

        // Log::info('[PDF] Record fetched successfully.', [
        //     'product_type' => $record->product_type,
        //     'subscriber_id' => optional($record->subscriber_detail)->id,
        //     'record_id' => $record->id
        // ]);

        $devices = []; // default empty list

        if (strtolower($record->product_type) == 'add devices/slots' || strtolower($record->product_type) == 'add devices/slots') {
            $subscriberId = $record->subscriber_detail->id ?? null;

            if ($subscriberId) {
                // Log::info('[PDF] Fetching assigned devices for subscriber.', ['subscriber_id' => $subscriberId]);

                try {
                    // Removed .where('status', 'filled')
                    $devices = SubscriberAssignedDevice::where('subscriber_id', $subscriberId)
                        ->get(['device_name', 'price'])
                        ->toArray();

                    // Log::info('[PDF] Devices fetched.', [
                    //     'count' => count($devices),
                    //     'devices' => $devices
                    // ]);
                } catch (\Exception $e) {
                    Log::error('[PDF] Error fetching devices.', ['error' => $e->getMessage()]);
                }
            } else {
                Log::warning('[PDF] Subscriber ID not found, skipping device fetch.');
            }
        } else {
            Log::info('[PDF] Product type is not \"add device/slot\", skipping device fetch.');
        }


        // Build logo as base64
        try {
            $logoPath = public_path('adminview/assets/images/email/logo_new.png');
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = file_get_contents($logoPath);
            $logo = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
            // Log::info('[PDF] Logo prepared successfully.');
        } catch (\Exception $e) {
            Log::error('[PDF] Failed to load logo.', ['error' => $e->getMessage()]);
            $logo = '';
        }

        // Load PDF view
        try {
            $pdf = Pdf::loadView('subscribers::activation.payment-history.template', [
                'record' => $record,
                'logo' => $logo,
                'devices' => $devices
            ]);
            // Log::info('[PDF] PDF view loaded successfully.');
        } catch (\Exception $e) {
            Log::error('[PDF] Failed to load PDF view.', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'true',
                'message' => 'Failed to create PDF.'
            ], 500);
        }

        // Prepare file paths
        $fileName = 'ISG.pdf';
        $pdfDir = storage_path('app/pdfs');
        $filePath = $pdfDir . '/' . $fileName;

        if (!file_exists($pdfDir)) {
            mkdir($pdfDir, 0755, true);
            Log::info('[PDF] PDF directory created.', ['path' => $pdfDir]);
        }

        // Save PDF
        try {
            $pdf->save($filePath);
            // Log::info('[PDF] PDF saved successfully.', ['file_path' => $filePath]);
        } catch (\Exception $e) {
            Log::error('[PDF] Failed to save PDF.', ['error' => $e->getMessage()]);
            return response()->json([
                'error' => 'true',
                'message' => 'Failed to save PDF.'
            ], 500);
        }

        if (!file_exists($filePath)) {
            Log::error('[PDF] Saved PDF file not found on disk.', ['file_path' => $filePath]);
            abort(404, 'PDF file not found.');
        }

        Log::info('[PDF] Returning PDF download response.', ['file_name' => $fileName]);

        return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Set a device as primary for a subscriber.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setPrimaryDevice(Request $request)
    {
        $id = $request->input('id');
        $subscriberId = $request->input('subscriber_id');

        if (!$id || !$subscriberId) {
            return response()->json(['status' => 'error', 'message' => 'Invalid parameters'], 400);
        }

        try {
            // Reset all devices for this subscriber to not primary
            SubscriberAssignedDevice::where('subscriber_id', $subscriberId)->update(['is_primary' => 0]);

            // Set the selected device as primary
            $record = SubscriberAssignedDevice::find($id);
            if ($record) {
                $record->is_primary = 1;
                $record->save();
                return response()->json(['status' => 'success', 'message' => 'Device set as primary successfully.']);
            }
            return response()->json(['status' => 'error', 'message' => 'Record not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Unlink a device from a slot.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    // public function unlinkDevice(Request $request)
    // {
    //     $id = $request->input('id');

    //     if (!$id) {
    //         return response()->json(['status' => 'error', 'message' => 'ID is required'], 400);
    //     }

    //     try {
    //         $record = SubscriberAssignedDevice::find($id);
    //         if ($record) {
    //             $record->device_id = null;
    //             $record->device_name = null;
    //             $record->is_primary = 0;
    //             $record->save();
    //             return response()->json(['status' => 'success', 'message' => 'Device unlinked successfully.']);
    //         }
    //         return response()->json(['status' => 'error', 'message' => 'Record not found'], 404);
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    //     }
    // }

    /**
     * Delete a slot entirely.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteSlot(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json(['status' => 'error', 'message' => 'ID is required'], 400);
        }

        try {
            $record = SubscriberAssignedDevice::find($id);
            if ($record) {
                $record->deletable = 1;
                $record->save();
                return response()->json(['status' => 'success', 'message' => 'Slot deleted successfully.']);
            }
            return response()->json(['status' => 'error', 'message' => 'Record not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
