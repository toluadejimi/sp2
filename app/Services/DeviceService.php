<?php
namespace App\Services;

use Jenssegers\Agent\Agent;

class DeviceService
{
    // Function to get the device and browser details
    public function getDeviceDetails()
    {
        $agent = new Agent();

        // Get device details
        $device = $agent->device();

        // Get browser details
        $browser = $agent->browser();

        // Combine the device and browser information
        return $device . ' - ' . $browser;
    }
}
