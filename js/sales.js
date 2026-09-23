/* =====================================================
   VERDEX SALES
   ===================================================== */


let sales = [

    {
        id: 1,
        customer: "Green Valley Market",
        product: "Lettuce",
        quantity: 40,
        amount: 5200,
        date: "2026-09-23",
        status: "Completed"
    },

    {
        id: 2,
        customer: "Fresh Basket Store",
        product: "Tomato",
        quantity: 30,
        amount: 4500,
        date: "2026-09-22",
        status: "Completed"
    },

    {
        id: 3,
        customer: "Juan Dela Cruz",
        product: "Bell Pepper",
        quantity: 20,
        amount: 3000,
        date: "2026-09-21",
        status: "Pending"
    },

    {
        id: 4,
        customer: "Organic Corner",
        product: "Cucumber",
        quantity: 25,
        amount: 3500,
        date: "2026-09-20",
        status: "Completed"
    },

    {
        id: 5,
        customer: "Fresh Market",
        product: "Lettuce",
        quantity: 35,
        amount: 4800,
        date: "2026-09-19",
        status: "Completed"
    }

];


/* =====================================================
   INITIALIZE
   ===================================================== */

document.addEventListener(
    "DOMContentLoaded",
    function () {

        renderSalesTable();

        updateStatistics();

        setDefaultDate();

    }
);


/* =====================================================
   RENDER TABLE
   ===================================================== */

function renderSalesTable() {

    const tableBody =
        document.getElementById(
            "salesTableBody"
        );


    tableBody.innerHTML = "";


    sales.forEach(
        sale => {

            const row =
                document.createElement("tr");


            row.onclick =
                function () {

                    showSaleDetails(
                        sale.id
                    );

                };


            row.innerHTML = `

                <td class="customer-cell">

                    ${escapeHTML(
                        sale.customer
                    )}

                </td>


                <td>

                    ${escapeHTML(
                        sale.product
                    )}

                </td>


                <td>

                    ${sale.quantity}

                </td>


                <td class="amount-cell">

                    ₱${formatNumber(
                        sale.amount
                    )}

                </td>


                <td>

                    ${formatDate(
                        sale.date
                    )}

                </td>


                <td>

                    <span class="status ${
                        sale.status === "Completed"
                            ? "completed"
                            : "pending"
                    }">

                        ${sale.status}

                    </span>

                </td>


                <td>

                    <button
                        class="table-action"
                        onclick="event.stopPropagation(); showSaleDetails(${sale.id})">

                        →

                    </button>

                </td>

            `;


            tableBody.appendChild(
                row
            );

        }
    );

}


/* =====================================================
   STATISTICS
   ===================================================== */

function updateStatistics() {

    const total =
        sales.reduce(
            (sum, sale) =>
                sum + Number(sale.amount),
            0
        );


    const items =
        sales.reduce(
            (sum, sale) =>
                sum + Number(sale.quantity),
            0
        );


    const pending =
        sales.filter(
            sale =>
                sale.status === "Pending"
        ).length;


    document.getElementById(
        "totalSales"
    ).textContent =
        `₱${formatNumber(total)}`;


    document.getElementById(
        "itemsSold"
    ).textContent =
        items;


    document.getElementById(
        "pendingSales"
    ).textContent =
        pending;

}


/* =====================================================
   ADD SALE
   ===================================================== */

function setupSaleForm() {

    const form =
        document.getElementById(
            "saleForm"
        );


    form.addEventListener(
        "submit",
        function (event) {

            event.preventDefault();


            const quantity =
                Number(
                    document.getElementById(
                        "saleQuantity"
                    ).value
                );


            const price =
                Number(
                    document.getElementById(
                        "salePrice"
                    ).value
                );


            const newSale = {

                id:
                    Date.now(),

                customer:
                    document.getElementById(
                        "customerName"
                    ).value.trim(),

                product:
                    document.getElementById(
                        "saleProduct"
                    ).value,

                quantity:
                    quantity,

                amount:
                    quantity * price,

                date:
                    document.getElementById(
                        "saleDate"
                    ).value,

                status:
                    document.getElementById(
                        "saleStatus"
                    ).value

            };


            sales.unshift(
                newSale
            );


            renderSalesTable();

            updateStatistics();

            closeSaleModal();

            form.reset();

        }
    );

}


/* =====================================================
   MODAL
   ===================================================== */

function openSaleModal() {

    document
        .getElementById(
            "saleModal"
        )
        .classList.add("show");


    setDefaultDate();

}


function closeSaleModal() {

    document
        .getElementById(
            "saleModal"
        )
        .classList.remove("show");

}


function setDefaultDate() {

    const input =
        document.getElementById(
            "saleDate"
        );


    if (!input.value) {

        const now =
            new Date();


        const year =
            now.getFullYear();


        const month =
            String(
                now.getMonth() + 1
            ).padStart(2, "0");


        const day =
            String(
                now.getDate()
            ).padStart(2, "0");


        input.value =
            `${year}-${month}-${day}`;

    }

}


/* =====================================================
   SALE DETAILS
   ===================================================== */

function showSaleDetails(id) {

    const sale =
        sales.find(
            item =>
                Number(item.id) ===
                Number(id)
        );


    if (!sale) {
        return;
    }


    document.getElementById(
        "saleDetailsCustomer"
    ).textContent =
        sale.customer;


    document.getElementById(
        "saleDetailsContent"
    ).innerHTML = `

        <div class="sale-detail-row">

            <strong>
                Product
            </strong>

            <span>
                ${escapeHTML(
                    sale.product
                )}
            </span>

        </div>


        <div class="sale-detail-row">

            <strong>
                Quantity
            </strong>

            <span>
                ${sale.quantity} items
            </span>

        </div>


        <div class="sale-detail-row">

            <strong>
                Amount
            </strong>

            <span>
                ₱${formatNumber(
                    sale.amount
                )}
            </span>

        </div>


        <div class="sale-detail-row">

            <strong>
                Date
            </strong>

            <span>
                ${formatDate(
                    sale.date
                )}
            </span>

        </div>


        <div class="sale-detail-row">

            <strong>
                Status
            </strong>

            <span>
                ${sale.status}
            </span>

        </div>

    `;


    document
        .getElementById(
            "saleDetailsModal"
        )
        .classList.add("show");

}


function closeSaleDetails() {

    document
        .getElementById(
            "saleDetailsModal"
        )
        .classList.remove("show");

}


/* =====================================================
   CHART PERIOD
   ===================================================== */

function changeSalesPeriod() {

    /*
     * The chart is currently visual only.
     *
     * Later we can connect it to the
     * actual sales database.
     */

    const period =
        document.getElementById(
            "salesPeriod"
        ).value;

    console.log(
        "Selected period:",
        period
    );

}


/* =====================================================
   VIEW ALL
   ===================================================== */

function showAllSales() {

    document
        .querySelector(
            ".recent-sales-card"
        )
        .scrollIntoView({
            behavior: "smooth"
        });

}


/* =====================================================
   HELPERS
   ===================================================== */

function formatNumber(number) {

    return Number(number)
        .toLocaleString(
            "en-PH",
            {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            }
        );

}


function formatDate(dateString) {

    const date =
        new Date(
            `${dateString}T00:00:00`
        );


    return date.toLocaleDateString(
        "en-US",
        {
            month: "short",
            day: "numeric",
            year: "numeric"
        }
    );

}


function escapeHTML(value) {

    if (
        value === null ||
        value === undefined
    ) {

        return "";

    }


    return String(value)

        .replace(
            /&/g,
            "&amp;"
        )

        .replace(
            /</g,
            "&lt;"
        )

        .replace(
            />/g,
            "&gt;"
        )

        .replace(
            /"/g,
            "&quot;"
        )

        .replace(
            /'/g,
            "&#039;"
        );

}


/* =====================================================
   START FORM
   ===================================================== */

document.addEventListener(
    "DOMContentLoaded",
    function () {

        setupSaleForm();

    }
);