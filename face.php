<?php if(empty($connection)){
  header('location:./404');
} else {
    /** Absensi menggunakan qr code */
    if($data_setting_absen['tipe_absen'] =='qrcode'){
        /** Didalam Radius */
        if($data_setting_absen['radius'] =='Y'){
            
            if(!$result_lokasi->num_rows > 0) {?>
               <script type="text/javascript">
                    swal({
                        text: "Lokasi tidak temukan, silahkan lengkapi Profil Anda!",
                        icon: "warning",
                        buttons: {
                            cancel: true,
                            confirm: true,
                        },
                        value: "yes",
                    })

                    .then((value) => {
                        if(value) {
                            setTimeout("location.href = './profile';");
                        }
                    });
                </script> 
            <?php }else{
                $data_lokasi = $result_lokasi->fetch_assoc();
                $lokasi_latitude =''.strip_tags($data_lokasi['lokasi_latitude']).', '.strip_tags($data_lokasi['lokasi_longitude']).'';
                $lokasi_radius = strip_tags($data_lokasi['lokasi_radius']);?>
               
            <script src="https://npmcdn.com/leaflet@0.7.7/dist/leaflet.js"></script>
            <script type="text/javascript">
                var latitude_building =L.latLng(<?php echo $lokasi_latitude;?>);
                navigator.geolocation.getCurrentPosition(function(location) {
                var latlng = new L.LatLng(location.coords.latitude, location.coords.longitude);
                var markerFrom = L.circleMarker(latitude_building, { color: "#F00", radius: 1 });
                var markerTo =  L.circleMarker(latlng);
                var from = markerFrom.getLatLng();
                var to = markerTo.getLatLng();
                var jarak = from.distanceTo(to).toFixed(0);
                var latitude =""+location.coords.latitude+","+location.coords.longitude+"";
                $(".latitude").text(latitude);
                var radius ='<?php echo $lokasi_radius;?>';

                if (<?php echo $lokasi_radius;?> > jarak){
                    swal({title: 'Success!', text:'Posisi Anda saat ini dalam radius', icon: 'success', timer: 1000,});
                        $(".result-radius").html('Posisi Anda saat ini dalam radius');
                    console.log('radius: '+radius);
                    console.log('jarak: '+jarak);
                    
                }else{
                    swal({title: 'Oops!', text:'Posisi Anda saat ini tidak didalam radius atau Jauh dari Radius!', icon: 'error', timer: 3000,});
                    $(".result-radius").html('Posisi Anda saat ini tidak didalam radius atau Jauh dari Radius!');
                    console.log('radius: '+radius);
                    console.log('jarak: '+jarak);
                }

                    var html5QrcodeScanner = new Html5QrcodeScanner(
                    "reader", { fps: 10, qrbox:280,facingMode: "environment"});

                    function onScanSuccess(decodedText, decodedResult) {
                        document.getElementById("my_audio").play();
                        var dataString = 'qrcode='+decodedText+'&latitude='+latitude+'&radius='+jarak+'';
                        $.ajax({
                            type: "POST",
                            url: "./module/absen-in/sw-proses.php?action=absen-qrcode-radius",
                            data: dataString,
                            cache: false,
                            async: false,
                            success: function (data) {
                                var results = data.split("/");
                                $results = results[0];
                                $results2 = results[1];
                                if ($results=='success') {
                                    swal({title: 'Berhasil!', text:$results2, icon: 'success', timer: 2000,});
                                    html5QrcodeScanner.clear();
                                    setTimeout("location.href = './';",2000);
                                } else {
                                    swal({title: 'Oops!', text:data, icon: 'error', timer: 2000,});
                                    //html5QrcodeScanner.render(onScanSuccess);
                                }
                            }
                        });
                    }
                    html5QrcodeScanner.render(onScanSuccess);
                });
            </script>  
        <?php }
     } else{
            /** Absen dengan qr code tanpa radius */
            ?>
           <script src="https://npmcdn.com/leaflet@0.7.7/dist/leaflet.js"></script>
            <script type="text/javascript">
                navigator.geolocation.getCurrentPosition(function(location) {
                var latitude =""+location.coords.latitude+","+location.coords.longitude+"";
                    console.log(latitude);
                    var html5QrcodeScanner = new Html5QrcodeScanner(
                    "reader", { fps: 10, qrbox:280,facingMode: "environment"});
                    function onScanSuccess(decodedText, decodedResult) {
                    // Handle on success condition with the decoded text or result.
                    //console.log(`Scan result: ${decodedText}`, decodedResult);
                        document.getElementById("my_audio").play();
                        var dataString = 'qrcode='+decodedText+'&latitude='+latitude+'';
                        $.ajax({
                            type: "POST",
                            url: "./module/absen-in/sw-proses.php?action=absen-qrcode",
                            data: dataString,
                            success: function (data) {
                                var results = data.split("/");
                                $results = results[0];
                                $results2 = results[1];
                                if ($results=='success') {
                                    swal({title: 'Berhasil!', text:$results2, icon: 'success', timer: 2000,});
                                    html5QrcodeScanner.clear();
                                    setTimeout("location.href = './';",2000);
                                } else {
                                    swal({title: 'Oops!', text:data, icon: 'error', timer: 2000,});
                                    //html5QrcodeScanner.render(onScanSuccess);
                                }
                            }
                        });
                    }
                    html5QrcodeScanner.render(onScanSuccess);

                    });
                </script>

        <?php }

        /** Absen dengan foto aja Radius*/
        } elseif($data_setting_absen['tipe_absen'] =='selfie'){
            
             /** Absen dengan foto selfie  dan radius*/
             if($data_setting_absen['radius'] =='Y'){
                if(!$result_lokasi->num_rows > 0) {?>
                    <script type="text/javascript">
                        swal({
                            text: "Lokasi tidak temukan, silahkan lengkapi Profil Anda!",
                            icon: "warning",
                            buttons: {
                                cancel: true,
                                confirm: true,
                            },
                            value: "yes",
                        })

                        .then((value) => {
                            if(value) {
                                setTimeout("location.href = './profile';");
                            }
                        });
                    </script> 
                    
                <?php }else{
                
                $data_lokasi = $result_lokasi->fetch_assoc();
                $lokasi_latitude =''.strip_tags($data_lokasi['lokasi_latitude']).', '.strip_tags($data_lokasi['lokasi_longitude']).'';
                $lokasi_radius = strip_tags($data_lokasi['lokasi_radius']);?>

                <script src="https://npmcdn.com/leaflet@0.7.7/dist/leaflet.js"></script>
                <script type="text/javascript">
                    
                    function loading(){
                    $('.loading-webcame').removeClass('d-none');
                    $('.loading-webcame').html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...<br>Pastikan cahaya cukup<br>Jika tidak respon ulangi kembali'
                    );
                        window.setTimeout(function () {
                            $('.loading-webcame').addClass('d-none');
                        }, 1000);
                    }

                    var latitude_building =L.latLng(<?php echo $lokasi_latitude;?>);
                    navigator.geolocation.getCurrentPosition(function(location) {
                    var latlng = new L.LatLng(location.coords.latitude, location.coords.longitude);
                    var markerFrom = L.circleMarker(latitude_building, { color: "#F00", radius:1 });
                    var markerTo =  L.circleMarker(latlng);
                    var from = markerFrom.getLatLng();
                    var to = markerTo.getLatLng();
                    var jarak = from.distanceTo(to).toFixed(0);
                    var latitude =""+location.coords.latitude+","+location.coords.longitude+"";
                    $(".latitude").text(latitude);
                    var radius ='<?php echo $lokasi_radius;?>';
                    var lokasi = '<?php echo $data_lokasi['lokasi_id'];?>';
    
                    if (<?php echo $lokasi_radius;?> > jarak){
                        swal({title: 'Success!', text:'Posisi Anda saat ini dalam radius', icon: 'success', timer:500,});
                            $(".result-radius").html('Posisi Anda saat ini dalam radius');
                        console.log('radius: '+radius);
                        console.log('jarak: '+jarak);
                        
                    }else{
                        swal({title: 'Oops!', text:'Posisi Anda saat ini tidak didalam radius atau Jauh dari Radius!', icon: 'error', timer: 1000,});
                        $(".result-radius").html('Posisi Anda saat ini tidak didalam radius atau Jauh dari Radius!');
                        console.log('radius: '+radius);
                        console.log('jarak: '+jarak);
                    }

        
                    /** Kamera */
                    const webcamElement = document.getElementById('webcam');
                    const canvasElement = document.getElementById('canvas');
                    const webcam = new Webcam(webcamElement, 'user', canvasElement);

                    $('.md-modal').addClass('md-show');
                    cameraStarted();
                    webcam.start()
                    .then(result =>{
                        cameraStarted();
                        console.log("webcam started");
                    })
                    .catch(err => {
                        displayError();
                    });
                    console.log("webcam started");
                    $("#webcam-switch").change(function () {
                        if(this.checked){
                            $('.md-modal').addClass('md-show');
                            webcam.start()
                                .then(result =>{
                                cameraStarted();
                                console.log("webcam started");
                                })
                                .catch(err => {
                                    displayError();
                                });
                        }
                        else {        
                            cameraStopped();
                            webcam.stop();
                            console.log("webcam stopped");
                            }        
                     });

                        $('.cameraFlip').click(function() {
                            webcam.flip();
                            webcam.start();  
                        });


                        function displayError(err = ''){
                            if(err!=''){
                                $("#errorMsg").html(err);
                            }
                            $("#errorMsg").removeClass("d-none");
                        }

                        function cameraStarted(){
                            $("#webcam-caption").html("on");
                            $("#webcam-control").removeClass("webcam-off");
                            $("#webcam-control").addClass("webcam-on");
                            $(".webcam-container").removeClass("d-none");
                            if( webcam.webcamList.length > 1){
                                $(".cameraFlip").removeClass('d-none');
                            }
                            $("#wpfront-scroll-top-container").addClass("d-none");
                            window.scrollTo(0, 0); 
                            //$('body').css('overflow-y','hidden');
                        }

                        $(".take-photo").click(function () {
                            beforeTakePhoto();
                            let picture = webcam.snap(300,300);
                            afterTakePhoto();
                            var img = new Image();
                            img.src = picture;
                            shutter.play();
                            loading();
                        
                            var canvas = document.getElementById("canvas");
                            var ctx = canvas.getContext("2d");
                            ctx.beginPath();
                            ctx.arc(100, 100, 50, 1.5 * Math.PI, 0.5 * Math.PI, false);
                            var imgData = canvas.toDataURL();
                            insert();
                        });

                        var shutter = new Audio();
                        //shutter.autoplay = true;
                        shutter.src = navigator.userAgent.match(/Firefox/) ? 'template/vendor/webcame/audio/snap.wav' : 'template/vendor/webcame/audio/snap.wav';

                        function insert(){
                            var dataURL = canvas.toDataURL();
                            $.ajax({
                                type: "POST",
                                url: "./module/absen-in/sw-proses.php?action=absen-selfie-radius",
                                data:{img:dataURL,latitude:latitude,radius:jarak,lokasi:lokasi},
                                success: function (data) {
                                    var results = data.split("/");
                                    $results = results[0];
                                    $results2 = results[1];
                                    if ($results=='success') {
                                        swal({title: 'Berhasil!', text:$results2, icon: 'success', timer: 2500,});
                                        setTimeout("location.href = './';",2500);
                                    } else {
                                        swal({title: 'Oops!', text:data, icon: 'error',timer: 2500,});
                                    }
                                }
                            });
                        } 

                        function beforeTakePhoto(){
                            $('#webcam-control').addClass('d-none');
                            $('.take-photo').addClass('d-none');
                            $('.cameraFlip').addClass('d-none');
                            $('.resume-camera').removeClass('d-none');
                        }

                        function afterTakePhoto(){
                            webcam.stop();
                            $('#canvas').removeClass('d-none');
                            $('#reflay').removeClass('d-none');
                        }

                        function removeCapture(){
                            $('#canvas').addClass('d-none');
                            $('#reflay').addClass('d-none');
                            $('#webcam-control').removeClass('d-none');
                            $('#cameraControls').removeClass('d-none');
                            $('.take-photo').removeClass('d-none');
                            $('.resume-camera').addClass('d-none');
                            $('.cameraFlip').removeClass('d-none');
                            
                        }

                        $(".resume-camera").click(function () {
                            webcam.stream()
                            .then(facingMode =>{
                                removeCapture();
                            });
                        });
            });
        </script>
        <?php } 
            }else{
            /** Absen dengan Selfie aja tanpa radius */
            if(!$result_lokasi->num_rows > 0) {?>
                    <script type="text/javascript">
                        swal({
                            text: "Lokasi tidak temukan, silahkan lengkapi Profil Anda!",
                            icon: "warning",
                            buttons: {
                                cancel: true,
                                confirm: true,
                            },
                            value: "yes",
                        })

                        .then((value) => {
                            if(value) {
                                setTimeout("location.href = './profile';");
                            }
                        });
                    </script> 
                    
                <?php }else{?>

            <script type="text/javascript">
                
                function loading(){
                $('.loading-webcame').removeClass('d-none');
                $('.loading-webcame').html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...<br>Pastikan cahaya cukup<br>Jika tidak respon ulangi kembali'
                );
                    window.setTimeout(function () {
                        $('.loading-webcame').addClass('d-none');
                    },1000);
                }
                
                navigator.geolocation.getCurrentPosition(function(location) {
                var latitude =""+location.coords.latitude+","+location.coords.longitude+"";
                console.log(latitude);
                
                /** Kamera */
                const webcamElement = document.getElementById('webcam');
                const canvasElement = document.getElementById('canvas');
                const webcam = new Webcam(webcamElement, 'user', canvasElement);

                $('.md-modal').addClass('md-show');
                cameraStarted();
                webcam.start()
                .then(result =>{
                    cameraStarted();
                    console.log("webcam started");
                })
                .catch(err => {
                    displayError();
                });
                console.log("webcam started");
                $("#webcam-switch").change(function () {
                    if(this.checked){
                        $('.md-modal').addClass('md-show');
                        webcam.start()
                            .then(result =>{
                            cameraStarted();
                            console.log("webcam started");
                            })
                            .catch(err => {
                                displayError();
                            });
                    }
                    else {        
                        cameraStopped();
                        webcam.stop();
                        console.log("webcam stopped");
                    }        
                });

                    $('.cameraFlip').click(function() {
                        webcam.flip();
                        webcam.start();  
                    });


                    function displayError(err = ''){
                        if(err!=''){
                            $("#errorMsg").html(err);
                        }
                        $("#errorMsg").removeClass("d-none");
                    }

                    function cameraStarted(){
                        $("#webcam-caption").html("on");
                        $("#webcam-control").removeClass("webcam-off");
                        $("#webcam-control").addClass("webcam-on");
                        $(".webcam-container").removeClass("d-none");
                        if( webcam.webcamList.length > 1){
                            $(".cameraFlip").removeClass('d-none');
                        }
                        $("#wpfront-scroll-top-container").addClass("d-none");
                        window.scrollTo(0, 0); 
                        //$('body').css('overflow-y','hidden');
                    }

                    $(".take-photo").click(function () {
                        beforeTakePhoto();
                        let picture = webcam.snap(300,300);
                        afterTakePhoto();
                        var img = new Image();
                        img.src = picture;
                        shutter.play();
                        loading();
                    
                        var canvas = document.getElementById("canvas");
                        var ctx = canvas.getContext("2d");
                        ctx.beginPath();
                        ctx.arc(100, 100, 50, 1.5 * Math.PI, 0.5 * Math.PI, false);
                        var imgData = canvas.toDataURL();
                        //alert(canvas.toDataURL());
                        insert();
                    });

                    var shutter = new Audio();
                    //shutter.autoplay = true;
                    shutter.src = navigator.userAgent.match(/Firefox/) ? 'template/vendor/webcame/audio/snap.wav' : 'template/vendor/webcame/audio/snap.wav';

                    function insert(){
                        var dataURL = canvas.toDataURL();
                        $.ajax({
                            type: "POST",
                            url: "./module/absen-in/sw-proses.php?action=absen-selfie",
                            data:{img:dataURL,latitude:latitude},
                            success: function (data) {
                                var results = data.split("/");
                                $results = results[0];
                                $results2 = results[1];
                                if ($results=='success') {
                                    swal({title: 'Berhasil!', text:$results2, icon: 'success', timer: 2500,});
                                    setTimeout("location.href = './';",2500);
                                } else {
                                    swal({title: 'Oops!', text:data, icon: 'error',timer: 2500,});
                                }
                            }
                        });
                    } 

                    function beforeTakePhoto(){
                        $('#webcam-control').addClass('d-none');
                        $('.take-photo').addClass('d-none');
                        $('.cameraFlip').addClass('d-none');
                        $('.resume-camera').removeClass('d-none');
                    }

                    function afterTakePhoto(){
                        webcam.stop();
                        $('#canvas').removeClass('d-none');
                        $('#reflay').removeClass('d-none');
                    }

                    function removeCapture(){
                        $('#canvas').addClass('d-none');
                        $('#reflay').addClass('d-none');
                        $('#webcam-control').removeClass('d-none');
                        $('#cameraControls').removeClass('d-none');
                        $('.take-photo').removeClass('d-none');
                        $('.resume-camera').addClass('d-none');
                        $('.cameraFlip').removeClass('d-none');
                        
                    }

                    $(".resume-camera").click(function () {
                        webcam.stream()
                        .then(facingMode =>{
                            removeCapture();
                        });
                    });
            
            });
        </script>
        <?php }}

        
        }else{
            /** Absen dengan foto selfie  dan radius recognition*/
            if($data_setting_absen['radius'] =='Y'){
                if(!$result_lokasi->num_rows > 0) {?>
                    <script type="text/javascript">
                        $( document ).ready(function() {
                            swal({
                            text: "Lokasi tidak temukan, silahkan lengkapi Profil Anda!",
                            icon: "warning",
                            buttons: {
                                cancel: true,
                                confirm: true,
                            },
                            value: "yes",
                            })

                            .then((value) => {
                                if(value) {
                                    setTimeout("location.href = './profile';");
                                }
                            });
                        });
                    </script> 
                    
                <?php }else{
                
                $query_get_user = "SELECT nama_lengkap,photo FROM user
                INNER JOIN recognition ON user.user_id=recognition.user_id WHERE user.user_id='$data_user[user_id]'";
                $result_get_user = $connection->query($query_get_user);
                if($result_get_user->num_rows > 0){
                    $row = $result_get_user->fetch_assoc();
                     $path = 'sw-content/labeled-images/'.$row['photo'].'';
                     $files = base64_encode(file_get_contents($path));
                     $url = 'data:image/png;base64,'.$files.'';
                }else{?>
                    <script type="text/javascript">
                        $( document ).ready(function() {
                            swal({
                            text: "Wajah Anda tidak ditemukan, Silahkan tambah photo wajah Anda sebelum absen!",
                            icon: "warning",
                            buttons: {
                                cancel: true,
                                confirm: true,
                            },
                            value: "yes",
                            })

                            .then((value) => {
                                if(value) {
                                    setTimeout("location.href = './recognition';");
                                }
                            });
                        });
                    </script>
                  
               <?php }

                $data_lokasi = $result_lokasi->fetch_assoc();
                $lokasi_latitude =''.strip_tags($data_lokasi['lokasi_latitude']).', '.strip_tags($data_lokasi['lokasi_longitude']).'';
                $lokasi_radius = strip_tags($data_lokasi['lokasi_radius']);?>

                <script src="https://npmcdn.com/leaflet@0.7.7/dist/leaflet.js"></script>
                <script type="text/javascript">
                    
                    function loading(){
                    $('.loading-webcame').removeClass('d-none');
                    $('.loading-webcame').html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...<br>Pastikan cahaya cukup<br>Jika tidak respon ulangi kembali'
                    );
                        window.setTimeout(function () {
                            $('.loading-webcame').addClass('d-none');
                        }, 5000);
                    }

                    var latitude_building =L.latLng(<?php echo $lokasi_latitude;?>);
                    navigator.geolocation.getCurrentPosition(function(location) {
                    var latlng = new L.LatLng(location.coords.latitude, location.coords.longitude);
                    var markerFrom = L.circleMarker(latitude_building, { color: "#F00", radius:1 });
                    var markerTo =  L.circleMarker(latlng);
                    var from = markerFrom.getLatLng();
                    var to = markerTo.getLatLng();
                    var jarak = from.distanceTo(to).toFixed(0);
                    var latitude =""+location.coords.latitude+","+location.coords.longitude+"";
                    $(".latitude").text(latitude);
                    var radius ='<?php echo $lokasi_radius;?>';
    
                    if (<?php echo $lokasi_radius;?> > jarak){
                        swal({title: 'Success!', text:'Posisi Anda saat ini dalam radius', icon: 'success', timer:500,});
                            $(".result-radius").html('Posisi Anda saat ini dalam radius');
                        console.log('radius: '+radius);
                        console.log('jarak: '+jarak);
                        
                    }else{
                        swal({title: 'Oops!', text:'Posisi Anda saat ini tidak didalam radius atau Jauh dari Radius!', icon: 'error', timer: 1000,});
                        $(".result-radius").html('Posisi Anda saat ini tidak didalam radius atau Jauh dari Radius!');
                        console.log('radius: '+radius);
                        console.log('jarak: '+jarak);
                    }

        
                    /** Kamera */
                    const webcamElement = document.getElementById('webcam');
                    const canvasElement = document.getElementById('canvas');
                    const webcam = new Webcam(webcamElement, 'user', canvasElement);

                    $('.md-modal').addClass('md-show');
                    cameraStarted();
                    webcam.start()
                    .then(result =>{
                        cameraStarted();
                        console.log("webcam started");
                    })
                    .catch(err => {
                        displayError();
                    });
                    console.log("webcam started");
                    $("#webcam-switch").change(function () {
                        if(this.checked){
                            $('.md-modal').addClass('md-show');
                            webcam.start()
                                .then(result =>{
                                cameraStarted();
                                console.log("webcam started");
                                })
                                .catch(err => {
                                    displayError();
                                });
                        }
                        else {        
                            cameraStopped();
                            webcam.stop();
                            console.log("webcam stopped");
                            }        
                        });

                        $('.cameraFlip').click(function() {
                            webcam.flip();
                            webcam.start();  
                        });


                        function displayError(err = ''){
                            if(err!=''){
                                $("#errorMsg").html(err);
                            }
                            $("#errorMsg").removeClass("d-none");
                        }

                        function cameraStarted(){
                            $('.flash').hide();
                            $("#webcam-caption").html("on");
                            $("#webcam-control").removeClass("webcam-off");
                            $("#webcam-control").addClass("webcam-on");
                            $(".webcam-container").removeClass("d-none");
                            if( webcam.webcamList.length > 1){
                                $(".cameraFlip").removeClass('d-none');
                            }
                            $("#wpfront-scroll-top-container").addClass("d-none");
                            window.scrollTo(0, 0); 
                            //$('body').css('overflow-y','hidden');
                        }

                        $(".take-photo").click(function () {
                            beforeTakePhoto();
                            let picture = webcam.snap(300,300);
                            afterTakePhoto();
                            var img = new Image();
                            img.src = picture;
                            shutter.play();
                            loading();
                            start();
                        
                            var canvas = document.getElementById("canvas");
                            var ctx = canvas.getContext("2d");
                            ctx.beginPath();
                            ctx.arc(100, 100, 50, 1.5 * Math.PI, 0.5 * Math.PI, false);
                            var imgData = canvas.toDataURL();
                        });

                    var shutter = new Audio();
                    //shutter.autoplay = true;
                    shutter.src = navigator.userAgent.match(/Firefox/) ? 'template/vendor/webcame/audio/snap.wav' : 'template/vendor/webcame/audio/snap.wav';

                    function insert(){
                        var dataURL = canvas.toDataURL();
                        $.ajax({
                            type: "POST",
                            url: "./module/absen-in/sw-proses.php?action=absen-selfie-radius",
                            data:{img:dataURL,latitude:latitude,radius:jarak},
                            success: function (data) {
                                var results = data.split("/");
                                $results = results[0];
                                $results2 = results[1];
                                if ($results=='success') {
                                    swal({title: 'Berhasil!', text:$results2, icon: 'success', timer: 2500,});
                                    setTimeout("location.href = './';",2500);
                                } else {
                                    swal({title: 'Oops!', text:data, icon: 'error',timer: 2500,});
                                }
                            }
                        });
                    }
                    

                        function beforeTakePhoto(){
                
                            $('#webcam-control').addClass('d-none');
                            $('.take-photo').addClass('d-none');
                            $('.cameraFlip').addClass('d-none');
                            $('.resume-camera').removeClass('d-none');
                        }

                        function afterTakePhoto(){
                            webcam.stop();
                            $('#canvas').removeClass('d-none');
                            $('#reflay').removeClass('d-none');
                        }

                        function removeCapture(){
                            $('#canvas').addClass('d-none');
                            $('#reflay').addClass('d-none');
                            $('#webcam-control').removeClass('d-none');
                            $('#cameraControls').removeClass('d-none');
                            $('.take-photo').removeClass('d-none');
                            $('.resume-camera').addClass('d-none');
                            $('.cameraFlip').removeClass('d-none');
                            
                        }

                        $(".resume-camera").click(function () {
                            webcam.stream()
                            .then(facingMode =>{
                                removeCapture();
                            });
                        });

                    /** ------------ */
                    Promise.all([
                        faceapi.nets.ssdMobilenetv1.loadFromUri("template/vendor/webcame/models"),
                        faceapi.nets.faceRecognitionNet.loadFromUri("template/vendor/webcame/models"),
                        faceapi.nets.faceLandmark68Net.loadFromUri('template/vendor/webcame/models'),
                        faceapi.nets.tinyFaceDetector.loadFromUri('template/vendor/webcame/models'),
                    ])

                    async function start() {
                        const container = document.createElement('div')
                        container.style.position = 'relative'
                        document.body.append(container)

                        const labeledFaceDescriptors = await loadLabeledImages()
                        const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6)
                        let image

                        const img = document.getElementById('canvas')
                        ///const displaySize = { width: image.width, height: image.height }
                        //faceapi.matchDimensions(canvas, displaySize)
                        //const detections = await faceapi.detectAllFaces(image).withFaceLandmarks().withFaceDescriptors()
                        let detections = await faceapi
                        .detectAllFaces(img)
                        .withFaceLandmarks()
                        .withFaceDescriptors()
                        //.withFaceExpressions()
                        const canvas = $('#reflay').get(0)
                        faceapi.matchDimensions(canvas, img)
                        
                        /*const detections = await faceapi
                        .detectAllFaces(image)
                        .withFaceLandmarks()
                        .withFaceDescriptors();*/
                        const resizedDetections = faceapi.resizeResults(detections, img)
                        
                        const results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor))
                        results.forEach((result, i) => {
                        const box = resizedDetections[i].detection.box
                        //const drawBox = new faceapi.draw.DrawBox(box, { label: result.toString() })
                        const drawBox = new faceapi.draw.DrawBox(box, {
                            label: result,
                            //boxColor: "#e60000",
                        });
                        drawBox.draw(canvas);

                        var str = result.toString()
                        const ID_pegawai = result._label.toString();
                        rating = parseFloat(str.substring(str.indexOf('(') + 1,str.indexOf(')')))
                        
                        if (detections.length > 0) {
                            if(rating < 0.5){
                                if(ID_pegawai == '<?php echo $data_user['nama_lengkap'];?>'){
                                    //$("#webcame")[0].pause();
                                    insert();
                                    console.log('Wajah sesuai');
                                }else{
                                //alert('wajah tidak ditemukan');
                                }
                            }else{
                                //alert('Wajah Tidak sesuai');
                            }
                        }
                    })
                    }

                    function loadLabeledImages(){
                        var label_user = $('.result-user').html();
                        const labels = ['<?php echo $data_user['nama_lengkap'];?>']
                        return Promise.all(
                            labels.map(async (label) => {
                                const img = await faceapi.fetchImage(`<?php echo $url;?>`)
                                const detections = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor()
                               
                                if (!detections) {
                                    //throw new Error(`no faces detected for ${label}`)
                                    swal({
                                    text: "Wajah Anda tidak jelas, Silahkan Daftarkan wajah kembali!",
                                    icon: "warning",
                                        buttons: {
                                            cancel: true,
                                            confirm: true,
                                        },
                                    value: "yes",
                                    })

                                    .then((value) => {
                                        if(value) {
                                            setTimeout("location.href = './recognition';");
                                        }
                                    });
                                        
                                }

                                const faceDescriptors = [detections.descriptor]
                                return new faceapi.LabeledFaceDescriptors(label, faceDescriptors);
                            })
                        )
                    }
               
            });
        </script>
        <?php } 
            }else{
            /** Absen dengan Selfie Recognition tanpa radius */?>
            <script type="text/javascript">
                <?php $query_get_user = "SELECT nama_lengkap,photo FROM user
                INNER JOIN recognition ON user.user_id=recognition.user_id WHERE user.user_id='$data_user[user_id]'";
                $result_get_user = $connection->query($query_get_user);
                if($result_get_user->num_rows > 0){
                    $row = $result_get_user->fetch_assoc();
                     $path = 'sw-content/labeled-images/'.$row['photo'].'';
                     $files = base64_encode(file_get_contents($path));
                     $url = 'data:image/png;base64,'.$files.'';
                }else{?>
                    $( document ).ready(function() {
                        swal({
                        text: "Wajah Anda tidak ditemukan, Silahkan tambah photo wajah Anda sebelum absen!",
                        icon: "warning",
                            buttons: {
                                cancel: true,
                                confirm: true,
                            },
                            value: "yes",
                        })

                        .then((value) => {
                            if(value) {
                                setTimeout("location.href = './recognition';");
                            }
                        });
                    });
                  
               <?php }?>
                
                function loading(){
                $('.loading-webcame').removeClass('d-none');
                $('.loading-webcame').html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...<br>Pastikan cahaya cukup<br>Jika tidak respon ulangi kembali'
                );
                    window.setTimeout(function () {
                        $('.loading-webcame').addClass('d-none');
                    }, 5000);
                }
                
                navigator.geolocation.getCurrentPosition(function(location) {
                var latitude =""+location.coords.latitude+","+location.coords.longitude+"";
                console.log(latitude);

                 /** Kamera */
                const webcamElement = document.getElementById('webcam');
                const canvasElement = document.getElementById('canvas');
                const webcam = new Webcam(webcamElement, 'user', canvasElement);

                $('.md-modal').addClass('md-show');
                cameraStarted();
                webcam.start()
                .then(result =>{
                    cameraStarted();
                    console.log("webcam started");
                })
                .catch(err => {
                    displayError();
                });
                console.log("webcam started");
                $("#webcam-switch").change(function () {
                    if(this.checked){
                        $('.md-modal').addClass('md-show');
                        webcam.start()
                            .then(result =>{
                            cameraStarted();
                            console.log("webcam started");
                            })
                            .catch(err => {
                                displayError();
                            });
                    }
                    else {        
                        cameraStopped();
                        webcam.stop();
                        console.log("webcam stopped");
                        }        
                    });

                    $('.cameraFlip').click(function() {
                        webcam.flip();
                        webcam.start();  
                    });


                    function displayError(err = ''){
                        if(err!=''){
                            $("#errorMsg").html(err);
                        }
                        $("#errorMsg").removeClass("d-none");
                    }

                    function cameraStarted(){
                        $('.flash').hide();
                        $("#webcam-caption").html("on");
                        $("#webcam-control").removeClass("webcam-off");
                        $("#webcam-control").addClass("webcam-on");
                        $(".webcam-container").removeClass("d-none");
                        if( webcam.webcamList.length > 1){
                            $(".cameraFlip").removeClass('d-none');
                        }
                        $("#wpfront-scroll-top-container").addClass("d-none");
                        window.scrollTo(0, 0); 
                        //$('body').css('overflow-y','hidden');
                    }

                    $(".take-photo").click(function () {
                        beforeTakePhoto();
                        let picture = webcam.snap(300,300);
                        afterTakePhoto();
                        var img = new Image();
                        img.src = picture;
                        shutter.play();
                        loading();
                        start();
                    
                        var canvas = document.getElementById("canvas");
                        var ctx = canvas.getContext("2d");
                        ctx.beginPath();
                        ctx.arc(100, 100, 50, 1.5 * Math.PI, 0.5 * Math.PI, false);
                        var imgData = canvas.toDataURL();
                    });

                    var shutter = new Audio();
                    //shutter.autoplay = true;
                    shutter.src = navigator.userAgent.match(/Firefox/) ? 'template/vendor/webcame/audio/snap.wav' : 'template/vendor/webcame/audio/snap.wav';

                    function insert(){
                        var dataURL = canvas.toDataURL();
                        $.ajax({
                            type: "POST",
                            url: "./module/absen-in/sw-proses.php?action=absen-selfie",
                            data:{img:dataURL,latitude:latitude},
                            success: function (data) {
                                var results = data.split("/");
                                $results = results[0];
                                $results2 = results[1];
                                if ($results=='success') {
                                    swal({title: 'Berhasil!', text:$results2, icon: 'success',});
                                    setTimeout("location.href = './';",2500);
                                } else {
                                    swal({title: 'Oops!', text:data, icon: 'error',timer: 2500,});
                                }
                            }
                        });
                    }
                

                    function beforeTakePhoto(){
            
                        $('#webcam-control').addClass('d-none');
                        $('.take-photo').addClass('d-none');
                        $('.cameraFlip').addClass('d-none');
                        $('.resume-camera').removeClass('d-none');
                    }

                    function afterTakePhoto(){
                        webcam.stop();
                        $('#canvas').removeClass('d-none');
                        $('#reflay').removeClass('d-none');
                    }

                    function removeCapture(){
                        $('#canvas').addClass('d-none');
                        $('#reflay').addClass('d-none');
                        $('#webcam-control').removeClass('d-none');
                        $('#cameraControls').removeClass('d-none');
                        $('.take-photo').removeClass('d-none');
                        $('.resume-camera').addClass('d-none');
                        $('.cameraFlip').removeClass('d-none');
                        
                    }

                    $(".resume-camera").click(function () {
                        webcam.stream()
                        .then(facingMode =>{
                            removeCapture();
                        });
                    });

                    /** ------------ */
                    Promise.all([
                        faceapi.nets.ssdMobilenetv1.loadFromUri("template/vendor/webcame/models"),
                        faceapi.nets.faceRecognitionNet.loadFromUri("template/vendor/webcame/models"),
                        faceapi.nets.faceLandmark68Net.loadFromUri('template/vendor/webcame/models'),
                        faceapi.nets.tinyFaceDetector.loadFromUri('template/vendor/webcame/models'),
                    ])

                    async function start() {
                        const container = document.createElement('div')
                        container.style.position = 'relative'
                        document.body.append(container)

                        const labeledFaceDescriptors = await loadLabeledImages()
                        const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6)
                        let image

                        const img = document.getElementById('canvas')
                        ///const displaySize = { width: image.width, height: image.height }
                        //faceapi.matchDimensions(canvas, displaySize)
                        //const detections = await faceapi.detectAllFaces(image).withFaceLandmarks().withFaceDescriptors()
                        let detections = await faceapi
                        .detectAllFaces(img)
                        .withFaceLandmarks()
                        .withFaceDescriptors()
                        //.withFaceExpressions()
                        const canvas = $('#reflay').get(0)
                        faceapi.matchDimensions(canvas, img)
                        
                        /*const detections = await faceapi
                        .detectAllFaces(image)
                        .withFaceLandmarks()
                        .withFaceDescriptors();*/
                        const resizedDetections = faceapi.resizeResults(detections, img)
                        
                        const results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor))
                        results.forEach((result, i) => {
                        const box = resizedDetections[i].detection.box
                        //const drawBox = new faceapi.draw.DrawBox(box, { label: result.toString() })
                        const drawBox = new faceapi.draw.DrawBox(box, {
                            label: result,
                            //boxColor: "#e60000",
                        });
                        drawBox.draw(canvas);

                        var str = result.toString()
                        const ID_pegawai = result._label.toString();
                        rating = parseFloat(str.substring(str.indexOf('(') + 1,str.indexOf(')')))
                        
                        if (detections.length > 0) {
                            if(rating < 0.5){
                                if(ID_pegawai == '<?php echo $data_user['nama_lengkap'];?>'){
                                    //$("#webcame")[0].pause();
                                    insert();
                                    console.log('Wajah sesuai');
                                }else{
                                //alert('wajah tidak ditemukan');
                                }
                            }else{
                                //alert('Wajah Tidak sesuai');
                            }
                        }
                    })
                    }

                    function loadLabeledImages(){
                        var label_user = $('.result-user').html();
                        const labels = ['<?php echo $data_user['nama_lengkap'];?>']
                        return Promise.all(
                            labels.map(async (label) => {
                                const img = await faceapi.fetchImage(`<?php echo $url;?>`)
                                const detections = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor()
                               
                                if (!detections) {
                                    //throw new Error(`no faces detected for ${label}`)
                                    swal({
                                    text: "Wajah Anda tidak jelas, Silahkan Daftarkan wajah kembali!",
                                    icon: "warning",
                                        buttons: {
                                            cancel: true,
                                            confirm: true,
                                        },
                                    value: "yes",
                                    })

                                    .then((value) => {
                                        if(value) {
                                            setTimeout("location.href = './recognition';");
                                        }
                                    });
                                        
                                }

                                const faceDescriptors = [detections.descriptor]
                                return new faceapi.LabeledFaceDescriptors(label, faceDescriptors);
                            })
                        )
                    }

                    
        });
    </script>
    <?php }
    }
}?>
