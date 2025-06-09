<?php

enum UPSRequestType: int
{
    case AddressValidation = 1;
    case AddressClassification = 2;
    case AddressValidationAndClassification = 3;
}
