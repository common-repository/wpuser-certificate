var imageViewer = document.getElementById("certification-viewer-image"),
    certificat_imageblob = document.getElementById("certificat_imageblob");

    jQuery("#certificationdetailsform").submit(async function (e) {
    // e.preventDefault();
    
    // var makeblobfromurl = await 
	// fetch(imageViewer.src)
	// .then(r => r.blob())
	// .catch((error) => {
	// 	console.log(error)
    // });
    
    // convertBlobToBase64 = (blob) => new Promise((resolve, reject) => {
    //     const reader = new FileReader;
    //     reader.onerror = reject;
    //     reader.onload = () => {
    //         resolve(reader.result);
    //     };

    //     reader.readAsDataURL(blob);
    // });
    
    // const base64String = await convertBlobToBase64(makeblobfromurl);
    // certificat_imageblob.value = base64String;

    this.submit();
    
})