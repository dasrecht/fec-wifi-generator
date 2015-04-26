# fec-wifi-generator
Small script that we use to generate the wifi codes for FEC

## Why?
To login in the IFI network you need an username/pass. These are separate per device and need to be generated via a website: https://www.uzh.ch/id/cl/dl/admin/ssl-dir/guestaccounts/index.php
You need a token to generate the codes, which is provided by the "Informatikdienste der UZH".
Instead of giving our attendees the Token code to generate their own account, we pregenerate the accounts and print it on their badge.
This script does that, super ugly, but it works.
Important: The Token allows a maximum number of accounts generated, so don't generate too many just for testing.

## How?
The script sends a POST request to https://www.uzh.ch/id/cl/iframe/dl/admin/ssl-dir/conference/eventid.php with the token and some fake information and saves the returned username/pass account information in a CSV File that is then downloaded.
Via the parameter `results_number` you can define how many codes are generated. I suggest to use 100 per batch.

## More?
Read the inline comments in the file: https://github.com/Schnitzel/fec-wifi-generator/blob/master/generate.php
