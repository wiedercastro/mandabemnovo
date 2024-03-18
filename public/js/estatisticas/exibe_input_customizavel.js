
const checaSeValorEstaCustomizado = () => {
  let inputDoPeriodo = document.getElementById("periodo");
  let customDiv = document.getElementById("exibeParaCustomizado");

  if (inputDoPeriodo.value === "customizado") {
    customDiv.style.display = "block";
  } else {
    customDiv.style.display = "none";
  }
}

document.getElementById("periodo").addEventListener("change", checaSeValorEstaCustomizado);