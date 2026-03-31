// PDF
const btnPDF = document.getElementById('btnPDF');
if (btnPDF) {
  btnPDF.addEventListener('click', async () => {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p','mm','a4');
    let y = 20;

    doc.setFontSize(14);
    doc.text(`Presupuesto #${window.ID_PRESUPUESTO}`, 15, y);
    y += 10;

    document.querySelectorAll('#tablaDetalles tbody tr').forEach(tr => {
      const prod = tr.querySelector('.product-select')?.selectedOptions[0].textContent || '';
      const cant = tr.querySelector('.cant').value || 0;
      const precio = parseFloat(tr.querySelector('.precio').value || 0).toFixed(2);
      const subtotal = parseFloat(cant * precio).toFixed(2);
      doc.text(`${prod} x${cant} $${precio} = $${subtotal}`, 15, y);
      y += 6;
    });

    doc.text(`TOTAL: $${document.getElementById('totalGeneral').innerText}`, 15, y+10);
    doc.save(`presupuesto_${window.ID_PRESUPUESTO}.pdf`);
  });
}

// Word
const btnWord = document.getElementById('btnWord');
if (btnWord) {
  btnWord.addEventListener('click', () => {
    let html = `<html><head><meta charset="utf-8"><title>Presupuesto #${window.ID_PRESUPUESTO}</title></head><body>`;
    html += `<h2>Presupuesto #${window.ID_PRESUPUESTO}</h2><table border="1" style="border-collapse:collapse;width:100%"><tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr>`;
    document.querySelectorAll('#tablaDetalles tbody tr').forEach(tr=>{
      const prod = tr.querySelector('.product-select')?.selectedOptions[0].textContent || '';
      const cant = tr.querySelector('.cant').value || 0;
      const precio = parseFloat(tr.querySelector('.precio').value || 0).toFixed(2);
      const subtotal = parseFloat(cant*precio).toFixed(2);
      html += `<tr><td>${prod}</td><td>${cant}</td><td>$${precio}</td><td>$${subtotal}</td></tr>`;
    });
    html += `</table><h3>TOTAL: $${document.getElementById('totalGeneral').innerText}</h3></body></html>`;
    const blob = new Blob(['\ufeff', html], { type: 'application/msword' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `presupuesto_${window.ID_PRESUPUESTO}.doc`;
    a.click();
    URL.revokeObjectURL(url);
  });
}

// Ticket TXT
const btnTicket = document.getElementById('btnTicket');
if (btnTicket) {
  btnTicket.addEventListener('click', () => {
    let text = `*** PRESUPUESTO #${window.ID_PRESUPUESTO} ***\n\n`;
    document.querySelectorAll('#tablaDetalles tbody tr').forEach(tr=>{
      const prod = tr.querySelector('.product-select')?.selectedOptions[0].textContent || '';
      const cant = tr.querySelector('.cant').value || 0;
      const precio = parseFloat(tr.querySelector('.precio').value || 0).toFixed(2);
      const subtotal = parseFloat(cant*precio).toFixed(2);
      text += `${prod}\n  x${cant}  $${precio}  = $${subtotal}\n\n`;
    });
    text += `TOTAL: $${document.getElementById('totalGeneral').innerText}\n`;
    const blob = new Blob([text], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `ticket_presupuesto_${window.ID_PRESUPUESTO}.txt`;
    a.click();
    URL.revokeObjectURL(url);
  });
}
