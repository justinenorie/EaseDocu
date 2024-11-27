$(document).ready(function () {
    function fetchRequests() {
        $.ajax({
            url: "../../../controller/FetchDataRequest.php?fetch=true",
            method: "GET",
            dataType: "json",
            /**
             * Function to handle the success response from the AJAX request
             * @param {object} response - The JSON response from the server
             * @description
             *  This function loops through the requests and dynamically generates
             *  table rows. It also appends the generated row to the table and
             *  calls the confirmationToggle() function to toggle the confirmation
             *  status of each request.
             */
            success: function (response) {
                const requests = response.documentRequests;
                const requestList = $("#request-list");
                requestList.empty(); // Clear the table before adding new rows

                // Loop through requests and dynamically generate table rows
                requests.forEach((request) => {
                    const totalPayment = parseFloat(request.totalPayment).toFixed(2);

                    const row = `
                        <tr data-id="${request._id.$oid}" class="data-row">
                            <td class="req-datalist">${escapeHtml(request.name)}</td>
                            <td class="req-datalist">${escapeHtml(request.studentID)}</td>
                            <td class="req-datalist">${escapeHtml(request.date)}</td>
                            <td class="req-datalist">₱${totalPayment}</td>
                        </tr>

                        <tr class="confirmation-status" id="confirmation-${request._id.$oid}" style="display: none;">
                            <td class="req-data" colspan="4">
                                <div class="status-details">
                                    <h3 class="status-text">Request Status: ${escapeHtml(request.status)}</h3>

                                    <div class="req-container">
                                        <div class="reqstatus-line">
                                            ${generateStatusIcons(request)}
                                        </div>
                                    </div>

                                    <div class="summary-container">
                                        <h3>Request Summary</h3>
                                        <div class="requested-documents">
                                            ${generateRequestedDocuments(request.requestedDocument, totalPayment)}
                                        </div>
                                        ${generateStatusForm(request)}
                                    </div>

                                </div>
                            </td>
                        </tr>
                    `;
                    requestList.append(row); // Append the generated row
                });
                confirmationToggle();
                console.log(response.documentRequests);
            },
            error: function (error) {
                console.error("Error fetching data:", error);
            },
        });
    }
     // Fetch requests on page load
     fetchRequests();
    
    function confirmationToggle() {
        const dataRows = document.querySelectorAll(".data-row");
        dataRows.forEach((row) => {
            const requestId = row.getAttribute("data-id");
            const confirmationRow = document.getElementById(`confirmation-${requestId}`);

            // Add click event to toggle confirmation row
            row.addEventListener("click", () => {
                const confirmationRows = document.querySelectorAll(".confirmation-status");
                confirmationRows.forEach((row) => {
                    if (row !== confirmationRow && row.classList.contains("show")) {
                        row.classList.remove("show");
                        row.classList.add("hide");
                        setTimeout(() => {
                            row.style.display = "none";
                            row.classList.remove("hide");
                        }, 500);
                    }
                });
                if (confirmationRow.classList.contains("show")) {
                    confirmationRow.classList.remove("show");
                    confirmationRow.classList.add("hide");
                    setTimeout(() => {
                        confirmationRow.style.display = "none";
                        confirmationRow.classList.remove("hide");
                    }, 500);
                } else {
                    confirmationRow.style.display = "table-row";
                    confirmationRow.classList.add("show");
                }
            });
        });
    }

    // Helper functions for generating dynamic content
    function escapeHtml(text) {
        return text.replace(/</g, "&lt;").replace(/>/g, "&gt;");
    }

    function generateStatusIcons(request) {
        const statuses = ["unpaid", "paid", "process", "ready"];
        const icons = {
            unpaid: "../../public/images/icons/warning.png",
            paid: "../../public/images/icons/dollar-sign.png",
            process: "../../public/images/icons/data-processing.png",
            ready: "../../public/images/icons/checked.png",
        };

        return statuses
            .map((status) => {
                const icon =
                    status === request.status
                        ? icons[status]
                        : "../../public/images/icons/standby-circle.png";
                return `
                <div class="reqstatus-name ${status}" data-student-id="${
                    request.studentID
                }">
                    <img class="icons" src="${icon}" alt="${status} Icon">
                    <p>${status.charAt(0).toUpperCase() + status.slice(1)}</p>
                </div>
            `;
            })
            .join("");
    }

    function generateRequestedDocuments(documents, totalPayment) {
        if (!documents || documents.length === 0) {
            return "<p>No documents requested.</p>";
        }

        const counts = documents.reduce((acc, doc) => {
            acc[doc] = (acc[doc] || 0) + 1;
            return acc;
        }, {});

        return (
            Object.entries(counts)
                .map(([doc, count]) => `<p>${count}x ${escapeHtml(doc)}</p>`)
                .join("") +
            `<p><strong>Total Payment:</strong> <strong class="prices">₱${totalPayment}</strong></p>`
        );
    }

    function generateStatusForm(request) {
        const statuses = {
            unpaid: "Confirm Payment",
            paid: "Confirm to Process",
            process: "Confirm Finished",
        };
        const buttonText = statuses[request.status] || "";
        const display = request.status === "ready" ? "none" : "block";

        return buttonText
            ? `
            <form id="status-update-form" method="POST" style="display: ${display};">
                <input type="hidden" name="studentID" value="${escapeHtml(
                    request.studentID
                )}">
                <input type="hidden" name="currentStatus" value="${escapeHtml(
                    request.status
                )}">
                <button class="confirm-btn">${buttonText}</button>
            </form>
        `
            : "";
    }
});


// TODO: Fix the filter
// Select all filter items
document.querySelectorAll(".filters nav ul li").forEach((item) => {
    item.addEventListener("click", function () {
        document
            .querySelectorAll(".filters nav ul li")
            .forEach((li) => li.classList.remove("active"));
        // Add active class to the clicked item
        item.classList.add("active");
    });
    // Initialization active for unpaid
    if (item.textContent.includes("UNPAID")) {
        item.classList.add("active");
    }
});
