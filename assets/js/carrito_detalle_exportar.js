// Exportar carrito en PDF, Word o Ticket (HTML print)
document.addEventListener("DOMContentLoaded", () => {
    const btnPDF = document.getElementById("btnPDF");
    const btnWord = document.getElementById("btnWord");
    const btnTicket = document.getElementById("btnTicket");

    function generarContenido() {
        const contenido = document.querySelector(".container").innerHTML;
        const logo = document.getElementById("logoHidden")?.outerHTML || "";
        return `
            <html>
            <head><title>Carrito #${window.ID_CARRITO}</title></head>
            <body>
                ${logo}
                <h2>Carrito #${window.ID_CARRITO}</h2>
                <p>Usuario: ${window.CARRITO_USUARIO} • Fecha: ${window.CARRITO_FECHA}</p>
                ${contenido}
            </body>
            </html>
        `;
    }

    if (btnPDF) {
        btnPDF.addEventListener("click", () => {
            const ventana = window.open("", "_blank");
            ventana.document.write(generarContenido());
            ventana.document.close();
            ventana.print(); // imprime como PDF
        });
    }

    if (btnWord) {
        btnWord.addEventListener("click", () => {
            const contenido = generarContenido();
            const blob = new Blob([contenido], { type: "application/msword" });
            const url = URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = `Carrito_${window.ID_CARRITO}.doc`;
            a.click();
        });
    }

    if (btnTicket) {
        btnTicket.addEventListener("click", () => {
            const ventana = window.open("", "_blank", "width=400,height=600");
            ventana.document.write(`
                <html><head><title>Ticket Carrito</title></head><body>
                <h3>Ticket Carrito #${window.ID_CARRITO}</h3>
                <p>Usuario: ${window.CARRITO_USUARIO}</p>
                <p>Fecha: ${window.CARRITO_FECHA}</p>
                <hr>
                ${document.querySelector("#tablaDetalles").outerHTML}
                <p><strong>Total: $${document.getElementById("totalGeneral").innerText}</strong></p>
                </body></html>
            `);
            ventana.document.close();
            ventana.print();
        });
    }
});
