<?php

namespace Contus\Subscribers\Observer;

use Contus\Subscribers\Events\DeviceAssigned;
use Contus\Subscribers\Model\SubscriberAssignedDevice;

class SubscriberAssignedDeviceObserver
{
    /**
     * Handle the SubscriberAssignedDevice "created" event.
     *
     * @param  \Contus\Subscribers\Model\SubscriberAssignedDevice  $assignedDevice
     * @return void
     */
    public function created(SubscriberAssignedDevice $assignedDevice)
    {
        // Broadcast the event to the activation page
        broadcast(new DeviceAssigned($assignedDevice));
    }

    /**
     * Handle the SubscriberAssignedDevice "updated" event.
     *
     * @param  \Contus\Subscribers\Model\SubscriberAssignedDevice  $assignedDevice
     * @return void
     */
    public function updated(SubscriberAssignedDevice $assignedDevice)
    {
        // Broadcast the event to the activation page if something changed
        if ($assignedDevice->wasRecentlyCreated) {
            // Already handled in created
        } else {
            // Optional: broadcast on update if needed
            // For now, only created is requested to trigger "auto-load"
        }
    }
}
