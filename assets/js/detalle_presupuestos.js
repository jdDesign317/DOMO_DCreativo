function formatMoney(n) { return Number(n).toFixed(2); }

function actualizarTotales() {
  let total = 0;
  document.querySelectorAll('#tablaDetalles tbody tr').forEach(tr => {
    const cant = parseFloat(tr.querySelector('.cant').value) || 0;
    const precio = parseFloat(tr.querySelector('.precio').value) || 0;
    const subtotal = cant * precio;
    tr.querySelector('.subtotal').innerText = '$' + formatMoney(subtotal);
    total += subtotal;
  });
  document.getElementById('totalGeneral').innerText = formatMoney(total);
}

function nuevaFila(id_producto = '', cantidad = 1, precio = 0) {
  const tbody = document.querySelector('#tablaDetalles tbody');
  const tr = document.createElement('tr');

  tr.innerHTML = `
    <td><select class="form-select product-select">${document.querySelector('.product-select').innerHTML}</select></td>
    <td><input type="number" class="form-control cant small-input" min="1" value="${cantidad}"></td>
    <td><input type="number" class="form-control precio" min="0" step="0.01" value="${precio}"></td>
    <td class="subtotal">$${formatMoney(cantidad * precio)}</td>
    <td>
      <button class="btn btn-sm btn-outline-danger btn-eliminar"><i class="bi bi-trash"></i></button>
      <button class="btn btn-sm btn-outline-secondary btn-clonar"><i class="bi bi-files"></i></button>
    </td>
  `;

  tbody.appendChild(tr);
  attachRowListeners(tr);
  actualizarTotales();
}

function attachRowListeners(tr) {
  tr.querySelector('.cant').addEventListener('input', actualizarTotales);
  tr.querySelector('.precio').addEventListener('input', actualizarTotales);

  tr.querySelector('.btn-eliminar').addEventListener('click', () => {
    tr.remove();
    actualizarTotales();
  });

  tr.querySelector('.btn-clonar').addEventListener('click', () => {
    const id_producto = tr.querySelector('.product-select').value;
    const cantidad = tr.querySelector('.cant').value;
    const precio = tr.querySelector('.precio').value;
    nuevaFila(id_producto, cantidad, precio);
  });
}

// Inicializar
document.querySelectorAll('#tablaDetalles tbody tr').forEach(tr => attachRowListeners(tr));
document.getElementById('btnAgregarFila').addEventListener('click', () => nuevaFila());
actualizarTotales();
