document.addEventListener("DOMContentLoaded", function () {
    new DataTable("#example", {
       scrollX: true
    })
 }), document.addEventListener("DOMContentLoaded", function () {
    new DataTable("#scroll-vertical", {
       scrollY: "210px",
       scrollCollapse: true,
       paging: false
    })
 }), document.addEventListener("DOMContentLoaded", function () {
    new DataTable("#scroll-horizontal", {
       scrollX: true
    })
 }),

 document.addEventListener("DOMContentLoaded", function () {
    new DataTable("#data-table", {
       scrollX: true,
       buttons: ["excel"]
    })
 }),

 document.addEventListener("DOMContentLoaded", function () {
   new DataTable("#data-table2", {
      scrollX: true
   })
}), 

document.addEventListener("DOMContentLoaded", function () {
   new DataTable("#data-table3", {
      scrollX: true
   })
}), 


document.addEventListener("DOMContentLoaded", function () {
    new DataTable("#alternative-pagination", {
       pagingType: "full_numbers"
    })
 }), $(document).ready(function () {
    var e = $("#add-rows").DataTable(),
       a = 1;
    $("#addRow").on("click", function () {
       e.row.add([a + ".1", a + ".2", a + ".3", a + ".4", a + ".5", a + ".6", a + ".7", a + ".8", a + ".9", a + ".10", a + ".11", a + ".12"]).draw(false), a++
    }), $("#addRow").click()
 }), $(document).ready(function () {
    $("#example").DataTable()
 }), document.addEventListener("DOMContentLoaded", function () {
    new DataTable("#fixed-header", {
       fixedHeader: true
    })
 }), document.addEventListener("DOMContentLoaded", function () {
    new DataTable("#model-datatables", {
       responsive: {
          details: {
             display: $.fn.dataTable.Responsive.display.modal({
                header: function (e) {
                   e = e.data();
                   return "Details for " + e[0] + " " + e[1]
                }
             }),
             renderer: $.fn.dataTable.Responsive.renderer.tableAll({
                tableClass: "table"
             })
          }
       }
    })
 }), document.addEventListener("DOMContentLoaded", function () {
    new DataTable("#buttons-datatables", {
       dom: "Bfrtip",
       buttons: ["copy", "csv", "excel", "print", "pdf"]
    })
 }), document.addEventListener("DOMContentLoaded", function () {
    new DataTable("#ajax-datatables", {
       ajax: "assets/json/datatable.json"
    })
 });
 