<?php  $session = session(); ?>

<?php  
$created_by = empty($session->get('created_by')) ? 0 : $session->get('created_by');
$id = empty($session->get('id')) ? 0 : $session->get('id');
?>  
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery.cookie@1.4.1/jquery.cookie.min.js"></script>
<script src="<?php echo base_url('assets/js/script.js') ?>"></script>
</body>
<script>
   $(function(){
      $('.sidebar-toggle').click(function(i){
         $('.main').toggleClass('toggled');
      });
   });
   $(function(){
      $('a#alertsDropdown').click(function(){
         let created_by = "<?php echo $created_by; ?>";
         let id = "<?php echo $id; ?>";
         console.log('criado por: ',created_by, 'Id: ', id)
         $.ajax({
               url:"<?php echo site_url('/update_view_notification'); ?>",
               method:"POST",
               data:{id:id, created_by:created_by },
               success:function(data){}
         })
      });
   });
   $(function(){
      $('.noselect.delete_notify').click(function(){
         let id_user = "<?php echo $id; ?>";
         $.ajax({
               url:"<?php echo site_url('/delete_notify'); ?>",
               method:"POST",
               data:{id_user:id_user},
               success:function(data){}
         })
      });
   });
   $(function(){
      $('.noselect.view_notify').click(function(){
         let id_user = "<?php echo $id; ?>";
         $.ajax({
               url:"<?php echo site_url('/view_notify'); ?>",
               method:"POST",
               data:{id_user:id_user},
               success:function(data){
                  visualizar_notificacoes()
               }
         })
      });
   });
</script>
</html>