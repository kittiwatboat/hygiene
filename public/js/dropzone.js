import Dropzone from "dropzone";

(function () {
    "use strict";

    Dropzone.autoDiscover = false;

    $(".dropzone").each(function () {
        let options = {
            url: $(this).attr('action'), // ใช้ URL ที่กำหนดในฟอร์ม
            method: "POST", // ใช้ POST method
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), // เพิ่ม CSRF token
            },
            acceptedFiles: "image/*", // จำกัดประเภทไฟล์ให้เป็นรูปภาพ
            init: function () {
                this.on("success", (file, response) => {
                    console.log("File uploaded successfully:", response);
                });
                this.on("error", (file, errorMessage) => {
                    console.error("Upload error:", errorMessage);
                });
            }
        };

        // Create Dropzone
        let dz = new Dropzone(this, options);
    });
})();
