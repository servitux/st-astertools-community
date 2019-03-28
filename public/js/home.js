/**
 * List of all the available skins
 *
 * @type Array
 */
var my_skins = [
  "skin-blue",
  "skin-black",
  "skin-red",
  "skin-yellow",
  "skin-purple",
  "skin-green",
  "skin-blue-light",
  "skin-black-light",
  "skin-red-light",
  "skin-yellow-light",
  "skin-purple-light",
  "skin-green-light"
];

//Add the change skin listener
$("[data-skin]").on('click', function (e) {
  e.preventDefault();
  change_skin($(this).data('skin'));
});

/**
* Replaces the old skin with the new skin
* @param String cls the new skin class
* @returns Boolean false to prevent link's default action
*/
function change_skin(cls) {
 $.each(my_skins, function (i) {
   $("body").removeClass(my_skins[i]);
 });

 $("body").addClass(cls);
 $("form[name=config] input[name=layout_skin]").val(cls);
 return false;
}

//Add the layout manager
$("[data-layout]").on('click', function () {
  change_layout($(this).data('layout'));
});

/**
 * Toggles layout classes
 *
 * @param String cls the layout class to toggle
 * @returns void
 */
function change_layout(cls) {
  $("body").toggleClass(cls);
  AdminLTE.layout.fixSidebar();
}

//search
$("ul[id=search] li a").on('click', function() {
  var dropdown = $("a[id=search_selection]");

  dropdown.data('search', $(this).data('search'));
  dropdown.html($(this).html() + " <span class='caret'></span>");
});

//llamadas por asterisk
$('body').on('click', '.asterisk_call', function(e) {
  var url = $(this).data('url')
  var number = $(this).data('number');
  var callerId = $(this).data('callerid');

  $.ajax({
    url: url,
    type: 'POST',
    data: { number: number, callerId: encodeURIComponent(callerId) },
    beforeSend: function (request) {
        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
    },
    error: function(response){
        alert("error llamando: " + response);
    }
  })
});
