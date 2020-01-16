<?php
require_once 'lib/check_auth.php';
$start=file_get_contents('lib/index_start.txt');
echo $start;
echo '
<section>
<h1>Курсы валют</h1>
<input type="date" id="date">
<div class="tbl-header">
    <table cellpadding="0" cellspacing="0" border="0">
      <thead>
        <tr>
          <th>Валюта</th>
          <th>Обозначение</th>
          <th>Значение курса</th>
        </tr>
      </thead>
    </table>
 </div>
<div class="tbl-content">
    <table cellpadding="0" cellspacing="0" border="0">
      <tbody></tbody>
    </table>
</div>
</section>
';
$end=file_get_contents('lib/index_end.txt');
echo $end;