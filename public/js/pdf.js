function PMensual() {
    var filename = prompt("Guardar como...", "Promedio Mensual por Asesor");
    if (document.getElementById("proMensual").msToBlob) {
        var blob = document.getElementById("proMensual").msToBlob();
        window.navigator.msSaveBlob(blob, filename + ".png")
    } else {
        link = document.getElementById("image1");
        link.href = document.getElementById("proMensual").toDataURL("image/png");
        link.download = filename
    }
}
function PENPPerfectas() {
    var filename = prompt("Guardar como...", "Porcentaje de Encuestas Por Asesor");
    if (document.getElementById("pastel").msToBlob) {
        var blob = document.getElementById("pastel").msToBlob();
        window.navigator.msSaveBlob(blob, filename + ".png")
    } else {
        link = document.getElementById("PENPPerfect");
        link.href = document.getElementById("pastel").toDataURL("image/png");
        link.download = filename
    }
}
function NegativaPNG() {
    var filename = prompt("Guardar como...", "Promedio Mensual por Asesor");
    if (document.getElementById("negativaCanvas").msToBlob) {
        var blob = document.getElementById("negativaCanvas").msToBlob();
        window.navigator.msSaveBlob(blob, filename + ".png")
    } else {
        link = document.getElementById("negativaPNG");
        link.href = document.getElementById("negativaCanvas").toDataURL("image/png");
        link.download = filename
    }
}
function PositivasPNG() {
    var filename = prompt("Guardar como...", "Promedio Mensual por Asesor");
    if (document.getElementById("positivaCanvas").msToBlob) {
        var blob = document.getElementById("positivaCanvas").msToBlob();
        window.navigator.msSaveBlob(blob, filename + ".png")
    } else {
        link = document.getElementById("positivaPNG");
        link.href = document.getElementById("positivaCanvas").toDataURL("image/png");
        link.download = filename
    }
}
function PreguntaPNG(){
    console.log(document.getElementById("PreporAse"));
    var filename = prompt("Guardar como...", "Promedio Mensual por Asesor");
    if (document.getElementById("PreporAse").msToBlob) {
        var blob = document.getElementById("PreporAse").msToBlob();
        window.navigator.msSaveBlob(blob, filename + ".png")
    } else {
        link = document.getElementById("prePorAsesPNG");
        link.href = document.getElementById("PreporAse").toDataURL("image/png");
        link.download = filename
    }
}
//PDFS
function ExportProMensual() {

kendo.drawing.drawDOM("#proMensual", {
forcePageBreak: ".page-break",
paperSize: "A4",
margin: {top: "2cm",bottom: "5cm",left:"1cm"},
scale: 0.6,
height: 200,
template: $("#page-template").html(),
keepTogether: ".prevent-split"
}).then(function(group) {
kendo.drawing.pdf.saveAs(group, "Promedio Mensual por Asesor.pdf")
})
}
function ExportPENPPerfectas() {
kendo.drawing.drawDOM("#pastel", {
forcePageBreak: ".page-break",
paperSize: "A4",
margin: {
    top: "2cm",
    bottom: "5cm",
    left: "0cm"
},
scale: 1,
height: 200,
template: $("#page-template4").html(),
keepTogether: ".prevent-split"
}).then(function(group) {
kendo.drawing.pdf.saveAs(group, "Exported_Itinerary.pdf")
})
}
function PositivaPDF() {
kendo.drawing.drawDOM("#positivaCanvas", {
forcePageBreak: ".page-break",
paperSize: "A4",
margin: {top: "2cm",bottom: "5cm",left:"1cm"},
scale: 0.6,
height: 200,
template: $("#page-template1").html(),
keepTogether: ".prevent-split"
}).then(function(group) {
kendo.drawing.pdf.saveAs(group, "Promedio Mensual por Asesor.pdf")
})
}
function NegativaPDF() {
kendo.drawing.drawDOM("#negativaCanvas", {
forcePageBreak: ".page-break",
paperSize: "A4",
margin: {top: "2cm",bottom: "5cm",left:"1cm"},
scale: 0.6,
height: 200,
template: $("#page-template3").html(),
keepTogether: ".prevent-split"
}).then(function(group) {
kendo.drawing.pdf.saveAs(group, "Promedio Mensual por Asesor.pdf")
})
}
function PreguntaPDF() {
kendo.drawing.drawDOM("#PreporAse", {
forcePageBreak: ".page-break",
paperSize: "A4",
margin: {top: "2cm",bottom: "5cm",left:"1cm"},
scale: 0.6,
height: 200,
template: $("#page-template5").html(),
keepTogether: ".prevent-split"
}).then(function(group) {
kendo.drawing.pdf.saveAs(group, "Promedio Mensual por Asesor.pdf")
})
}
