<?php
set_time_limit(900);

$token_params = array(
  'token' => 'XXXXXX', #Fill with token
  'enddate' => '2013.08.31',
  'submitInfo' => 'submit',
  'firstname' => 'FEC13',
  'surname' => 'FEC13',
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
  $token_params['email'] = 'user' . $index . '@frontendconf.ch';
  $index++;
  $query = array();
  foreach ($token_params as $key => $value) {
    $query[] = $key . '=' . $value;
  }
  curl_setopt($curl, CURLOPT_URL, 'https://www.uzh.ch/id/cl/iframe/dl/admin/ssl-dir/conference/eventid.php');
  curl_setopt($curl, CURLOPT_POSTFIELDS, implode('&', $query));
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  $result = curl_exec($curl);
  $curl_errno = curl_errno($curl);
  if ($curl_errno == 0) {
    // Get the username.
    $words = explode("<td>Username: </td><td>", $result);
    $words = explode('</td>', $words[1]);
    $username = $words[0];

    // Get the password.
    $words = explode("<td>Password: </td><td>", $result);
    $words = explode('</td>', $words[1]);
    $password = $words[0];
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
