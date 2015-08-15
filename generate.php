<?php
set_time_limit(900);

$token_params = array(
  'Requests[token]' => 'XXXXX', #Fill with token
  'Accounts[firstname]' => 'FEC2015',
  'Accounts[name]' => 'FEC2015'
);
if (!$_GET['results_number']) {
  echo 'No "results_number" specified in the URL query parameters.';
}
$results[] = array(
  'username', 'password',
);
$curl = curl_init();
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
$index = time();
for ($i = 1; $i <= $_GET['results_number']; $i++) {
  $token_params['Accounts[email]'] = 'user' . $index . '@frontendconf.ch';
  $index++;
  $query = array();
  foreach ($token_params as $key => $value) {
    $query[] = $key . '=' . $value;
  }
  curl_setopt($curl, CURLOPT_URL, 'https://www.uzh.ch/id/cl/dl/admin/ssl-dir/guestaccounts/index.php/accounts/get');
  curl_setopt($curl, CURLOPT_POSTFIELDS, implode('&', $query));
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  $result = curl_exec($curl);
  $curl_errno = curl_errno($curl);
  if ($curl_errno == 0) {
    // Get the username.
    $words = explode("Username: ", $result);
    $words = explode('<br/>', $words[1]);
    $username = $words[0];

    // // Get the password.
    $words = explode("Password: ", $result);
    $words = explode('</div>', $words[1]);
    $password = trim($words[0]);

    if ($username && $password) {
      $results[] = array(
        'username' => $username,
        'password' => $password,
      );
    }
  }
  sleep(1);
}
curl_close($curl);

// Print the results.
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename=credentials.csv');
header('Pragma: no-cache');
header("Expires: 0");

$outstream = fopen("php://output", 'w');
foreach($results as $result){
  fputcsv($outstream, $result);
}
fclose($outstream);
echo file_get_contents('php://output');

exit();
