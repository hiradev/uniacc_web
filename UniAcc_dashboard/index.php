<!DOCTYPE html>
<html lang="en">
<?php require_once('check_login.php');?>
<?php include('head.php');?>
<?php include 'connect.php';?>
<?php include('header.php');?>
<?php include('sidebar.php');?>
<?php
 date_default_timezone_set('Asia/Kolkata');
 $current_date = date('Y-m-d');?>
   
    <div id="main-wrapper">
        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Dashboard</h3> </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card p-30">
                            <div class="media">
                                <div class="media-left meida media-middle">
                                    <span><i class="fa fa-address-card f-s-40 color-primary"></i></span>
                                </div>
                                <div class="media-body media-text-right">
                                    <?php $sql="SELECT COUNT(*) FROM `tbl_rooms`";
                                $res = $conn->query($sql);
                                $row=mysqli_fetch_array($res);?> 
                                    <h2 class="color-black"><?php echo $row[0];?></h2>
                                    <p class="m-b-0">Total rooms</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-30">
                            <div class="media">
                                <div class="media-left meida media-middle">
                                    <span><i class="fa fa-users f-s-40 color-success"></i></span>
                                </div>
                                <div class="media-body media-text-right">
                                    <?php $sql="SELECT COUNT(*) FROM `tbl_customer`";
                                $res = $conn->query($sql);
                                $row=mysqli_fetch_array($res);?> 
                                    <h2 class="color-black"><?php echo $row[0];?></h2>
                                    <p class="m-b-0">total customers</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card p-30">
                            <div class="media">
                                <div class="media-left meida media-middle">
                                    <span><i class="fa fa-book f-s-40 color-warning"></i></span>
                                </div>
                                <div class="media-body media-text-right">
                                    <?php $sql="SELECT COUNT(*) FROM `tbl_booking`";
                                $res = $conn->query($sql);
                                $row=mysqli_fetch_array($res);?> 
                                    <h2 class="color-black"><?php echo $row[0];?></h2>
                                    <p class="m-b-0">total bookings</p>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                </div>
           
                
        
                <div class="row justify">
                    <div class="col-md-10">
                        <div class="card p-30" style="width:1000px;height: 900px">
                            <div class="box box-danger">
                                <div class="box-body ">
                                  <div id="calendar">
                                      
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

             <?php include('footer.php');?>
<script>
  $(function () {
    <?php
    include 'connect.php';
    $sql = "SELECT * FROM `tbl_booking`";
     $result = $conn->query($sql);
  $i=0;
  $display_appoint=array();
   while($row = $result->fetch_assoc()) { 
    $sql2 = "SELECT * FROM `tbl_customer` WHERE id='".$row['name']."'";
    $result2=$conn->query($sql2);
    $row2=$result2->fetch_assoc();
    $sql3 = "SELECT * FROM `tbl_rooms` WHERE id='".$row['roomname']."'";
    $result3=$conn->query($sql3);
    $row3=$result3->fetch_assoc();
    $display_appoint[$i]['name']=$row2['name'];
    $display_appoint[$i]['fromdate']=$row['fromdate'];
    $display_appoint[$i]['todate']=$row['todate'];
    $display_appoint[$i]['roomname']=$row3['roomname'];
    $display_appoint[$i]['color']=$row3['color'];
    $i++;
}
    ?>

    function init_events(ele) {
      ele.each(function () {

        var eventObject = {
          title: $.trim($(this).text()) 
        }

        $(this).data('eventObject', eventObject)

        $(this).draggable({
          zIndex        : 1070,
          revert        : true, 
          revertDuration: 0 
        })

      })
    }

    init_events($('#external-events div.external-event'))

    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()
    $('#calendar').fullCalendar({
      header    : {
        left  : 'prev,next today',
        center: 'title',
        right : 'month,agendaWeek,agendaDay'
      },
      buttonText: {
        today: 'today',
        month: 'month',
        week : 'week',
        day  : 'day'
      },
      events    : [
      <?php foreach ($display_appoint as $value) { ?>
        {
          title          : '<?php echo $value['name'],$value['roomname'] ?>',
          start          : new Date(<?php echo date("Y", strtotime($value['fromdate'])); ?>,
            <?php echo date("m", strtotime($value['fromdate'])); ?>-1,
            <?php echo date("d", strtotime($value['fromdate'])); ?>),
          end            : new Date(<?php echo date("Y", strtotime($value['todate'])); ?>,
            <?php echo date("m", strtotime($value['todate'])); ?>-1,
            <?php echo date("d", strtotime($value['todate'])); ?>+1),
          backgroundColor: '<?php echo $value['color']?>', 
          borderColor    : '<?php echo $value['color']?>' 
        },
        <?php } ?>
      ],
      editable  : true,
      droppable : true, 
      drop      : function (date, allDay) {

        var originalEventObject = $(this).data('eventObject')

        var copiedEventObject = $.extend({}, originalEventObject)

        copiedEventObject.start           = date
        copiedEventObject.allDay          = allDay
        copiedEventObject.backgroundColor = $(this).css('background-color')
        copiedEventObject.borderColor     = $(this).css('border-color')

        $('#calendar').fullCalendar('renderEvent', copiedEventObject, true)

        if ($('#drop-remove').is(':checked')) {
          $(this).remove()
        }

      }
    })

    var currColor = '#3c8dbc'
    var colorChooser = $('#color-chooser-btn')
    $('#color-chooser > li > a').click(function (e) {
      e.preventDefault()
      currColor = $(this).css('color')
      $('#add-new-event').css({ 'background-color': currColor, 'border-color': currColor })
    })
    $('#add-new-event').click(function (e) {
      e.preventDefault()
      var val = $('#new-event').val()
      if (val.length == 0) {
        return
      }

      var event = $('<div />')
      event.css({
        'background-color': currColor,
        'border-color'    : currColor,
        'color'           : '#fff'
      }).addClass('external-event')
      event.html(val)
      $('#external-events').prepend(event)

      init_events(event)

      $('#new-event').val('')
    })
  })
</script>
<?php if(!empty($_SESSION['success'])) {  ?>
<div class="popup popup--icon -success js_success-popup popup--visible">
  <div class="popup__background"></div>
  <div class="popup__content">
    <h3 class="popup__content__title">
      Success 
    </h1>
    <p><?php echo $_SESSION['success']; ?></p>
    <p>
      <button class="button button--success" data-for="js_success-popup">Close</button>
    </p>
  </div>
</div>
<?php unset($_SESSION["success"]);  
} ?>
<?php if(!empty($_SESSION['error'])) {  ?>
<div class="popup popup--icon -error js_error-popup popup--visible">
  <div class="popup__background"></div>
  <div class="popup__content">
    <h3 class="popup__content__title">
      Error 
    </h1>
    <p><?php echo $_SESSION['error']; ?></p>
    <p>
      <button class="button button--error" data-for="js_error-popup">Close</button>
    </p>
  </div>
</div>
<?php unset($_SESSION["error"]);  } ?>
    <script> 
      var addButtonTrigger = function addButtonTrigger(el) {
  el.addEventListener('click', function () {
    var popupEl = document.querySelector('.' + el.dataset.for);
    popupEl.classList.toggle('popup--visible');
  });
};

Array.from(document.querySelectorAll('button[data-for]')).
forEach(addButtonTrigger);
    </script>
</body>

</html>