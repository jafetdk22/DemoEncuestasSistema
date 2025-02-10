<script src="https://cdn.rawgit.com/zenorocha/clipboard.js/v1.5.3/dist/clipboard.min.js"></script>
function lerma(id_elemento) {
    var aux = document.createElement("input");
    console.log(id_elemento);
    aux.setAttribute("value", document.getElementById(id_elemento).innerHTML);
    document.body.appendChild(aux);
    aux.select();
    document.execCommand("copy");
    document.body.removeChild(aux);
  }