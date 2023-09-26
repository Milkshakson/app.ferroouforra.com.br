<?php
namespace App\Providers;

use App\Libraries\APIFF;

class DonationProvider extends APIFF
{
    public function createDonation(array $data = [])
    {
        try {
            return $this->consumeEndpoint('POST', '/donation/create', $data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateDonation(array $data = [])
    {
        try {
            return $this->consumeEndpoint('POST', '/donation/update', $data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function listaDonations()
    {
        try {
            return $this->consumeEndpoint('GET', "/donation/findByToken");
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function findDonationByIdentificador($txID, $data = [])
    {
        try {
            return $this->consumeEndpoint('GET', "/donation/findByIdentificador/$txID");
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
