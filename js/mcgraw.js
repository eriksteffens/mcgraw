

/*
getDBItem
Retrieves an item record from the database and then populates the modal with the data
*/
function getDBItem(id,page){
  //alert("getting item " + id)

	$.ajax({     
     type: "POST",
     url: "http://mcgraw.mcservices.com/mcgrawAJAX.php?t=" + page + "&o=" + id,
     success: function (data) {

     	//The AJAX request will return a bunch of JSON so we will need to parse it.
     	var arr = $.parseJSON(data);
     	for (var key in arr) {
        	var index = key;
     		var objects = $("#" + index);
     		if(objects[0] == undefined){
     			//the ID of file paths differs slightly accomodate that condition
     			objects = $("#" + index + "_path");
     		}
     		var type = objects[0].tagName;
     		if(objects.hasClass("file-path") && arr[key]){
     			//set the download link for a file
     			$("#" + index+ "_get").attr("href","uploads/"+arr[key]);target="_blank"
     			$("#" + index+ "_get").attr("target","_blank");
     		}

               

     		//if the type of the field is a select box, select the one desired.
     		if(type == "SELECT" && arr[key]){
     			objects.val(arr[key]);
     			objects.material_select();
     		}else{
     			//otherwise its just a freeform entry field so just set the data.
				$("#" + index).val(arr[key]);
		        if(!arr[key] || arr[key] == "" ){
		        	//have the empty validation update occording to the new data.
		          $("#check-" + index).addClass("check-error");

		        }

     		
     		}
     		if(arr[key] && type != "SELECT"){
     			$("label[for=" + index+"]").addClass("active");
     		}
     	}

     	//set up links to related elements.
          var gos = $("#" + "Tenant"+ "_go");
               gos.each(function(){
                    var href = $(this).attr("href");
                    $(this).attr("href",href.replace("[id]", arr["Tenant"]) );
               });
          var gos = $("#" + "Asset"+ "_go");
               gos.each(function(){
                    var href = $(this).attr("href");
                    $(this).attr("href",href.replace("[id]", arr["Asset"]) );
               });

     	}
     }
 );
}

$(document).ready(function() {
    $('select').material_select();

    if (window.location.hash != "") {
    	//auto scroll to the record in the list (happens if linked to from another page)
        $('html, body').animate({
           scrollTop: $(window.location.hash).offset().top
         }, 1000);

        var hash = "#btn-" + window.location.hash.substring(1);
        var btn = $( hash);
        btn.click();
        $("#modal1").css("display", "block");
      }
});