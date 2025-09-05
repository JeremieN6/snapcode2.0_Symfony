<?php

namespace App\Service;

class ContactCardProvider
{
    public function __construct(
        private readonly string $contactFirstName,
        private readonly string $contactLastName,
        private readonly string $contactOrg,
        private readonly string $contactTitle,
        private readonly string $contactEmail,
        private readonly string $contactPhone,
        private readonly string $contactWebsite,
    ) {}

    public function getVcardData(): array
    {
        return [
            'firstName' => $this->contactFirstName,
            'lastName' => $this->contactLastName,
            'org' => $this->contactOrg,
            'title' => $this->contactTitle,
            'email' => $this->contactEmail,
            'phone' => $this->contactPhone,
            'website' => $this->contactWebsite,
        ];
    }
}
