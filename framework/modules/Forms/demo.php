<?php
$form = new Form();
echo $form->create(array('id'=>'form_1','name'=>'form_1','method'=>'post','action'=>'process_1.php'));

$params = array(
   array(
       'name'=>'like_pie',
       'value'=>'yes',
       'label'=>'Yes'

   ),
   array(
       'name'=>'like_pie',
       'value'=>'no',
       'label'=>'No'
   )
);
echo '<div>You like pie?</div>';
echo $form->radioMulti($params);

echo $form->submit(array('id'=>'form_1-submit','value'=>'post pie'));

echo $form->close();
?>