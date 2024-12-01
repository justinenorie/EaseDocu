$(document).ready(function () {
    /**
     * Function to handle the success response from the AJAX request
     * @param {object} response - The JSON response from the server
     * @description
     *  This function loops through the requests and dynamically generates
     *  table rows. It also appends the generated row to the table and
     *  calls the confirmationToggle() function to toggle the confirmation
     *  status of each request.
     */
    function fetchRequests(status = "", openRowId = null, query = "") {
        const url = `../../api/FetchDataRequest.php?fetch=true${status ? `&status=${status}` : ""}${query ? `&query=${encodeURIComponent(query)}` : ""}&_=${new Date().getTime()}`;

        $.ajax({
            url: url,
            method: "GET",
            dataType: "json",
            success: function (response) {
                const requests = response.documentRequests;
                const requestList = $("#request-list");
                requestList.empty(); // Clear the table before adding new rows

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
                                    <h3 class="status-text">Request Status</h3>
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
                                        ${buttonStatusForm(request)}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `;
                    requestList.append(row);
                });

                confirmationToggle();

                // Keep the selected confirmation row open
                if (openRowId) {
                    const confirmationRow = $(`#confirmation-${openRowId}`);
                    confirmationRow.show().addClass("show");
                }
            },
            error: function (error) {
                console.error("Error fetching data:", error);
            },
        });
    }

    // Search Functionality input
    document.querySelector(".search-input").addEventListener("input", function (e) {
        document.querySelectorAll(".filters nav ul li").forEach((li) => {
            li.classList.remove("active");
        });
        const searchQuery = e.target.value.trim();
        fetchRequests("", null, searchQuery);
    });

    // Search Functionality Click
    document.querySelector(".search-input").addEventListener("click", function (e) {
        document.querySelectorAll(".filters nav ul li").forEach((li) => {
            li.classList.remove("active");
        });
        const searchQuery = e.target.value.trim();
        fetchRequests("", null, searchQuery);
    });

    // Filter requests based on the selected status 
    document.querySelectorAll(".filters nav ul li").forEach((item) => {
        let status;
        item.addEventListener("click", function (event) {
            event.preventDefault(); // Prevent default link behavior

            // Determine the status based on the clicked item's text
            const statusText = item.textContent.trim().toLowerCase();

            document.querySelectorAll(".filters nav ul li").forEach((li) => {
                li.classList.remove("active");
            });
            item.classList.add("active");

            // Match the clicked item's text to the corresponding status
            if (statusText.includes("unpaid")) {
                status = "unpaid";
            } else if (statusText.includes("paid")) {
                status = "paid";
            } else if (statusText.includes("process")) {
                status = "process";
            } else if (statusText.includes("finished")) {
                status = "ready";
            }
            fetchRequests(status);
        });

        // Initialize 'unpaid' as active by default
        if (item.textContent.includes("UNPAID")) {
            item.classList.add("active");
            fetchRequests("unpaid");
        }
    });

    //Confirmation toggle for more info
    function confirmationToggle() {
        const dataRows = document.querySelectorAll(".data-row");
        dataRows.forEach((row) => {
            const requestId = row.getAttribute("data-id");
            const confirmationRow = document.getElementById(
                `confirmation-${requestId}`
            );

            // Add click event to toggle confirmation row
            row.addEventListener("click", () => {
                const confirmationRows = document.querySelectorAll(
                    ".confirmation-status"
                );
                confirmationRows.forEach((row) => {
                    if (
                        row !== confirmationRow &&
                        row.classList.contains("show")
                    ) {
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

    //Button status function
    function buttonStatusForm(request) {
        const statuses = {
            unpaid: "Confirm Payment",
            paid: "Confirm to Process",
            process: "Set Appointment Pick-Up Date",
        };
        const buttonText = statuses[request.status] || "";
        const display = request.status === "ready" ? "none" : "block";

        return buttonText
            ? `
            <form class="status-update-form" data-id="${escapeHtml(
                request._id.$oid
            )}" data-current-status="${escapeHtml(
                request.status
            )}" style="display: ${display};">
                <button type="button" class="confirm-btn">${buttonText}</button>
            </form>
        `
            : "";
    }

    // Handle form submission without refreshing the page
    $(document).on("click", ".status-update-form .confirm-btn", function (e) {
        e.preventDefault(); // Prevent form submission

        const form = $(this).closest(".status-update-form");
        const studentObjectID = form.data("id");
        const currentStatus = form.data("current-status");

        if (currentStatus === "process") {
            // Call the date and time selection function
            SelectTimeDate((date, time) => {
                $.ajax({
                    url: "../../api/FetchDataRequest.php",
                    method: "POST",
                    data: { studentObjectID, currentStatus, date, time },
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            // Refresh requests and keep the selected row open
                            fetchRequests(currentStatus);
                        } else {
                            alert("Failed to update status. Please try again.");
                        }
                    },
                    error: function (error) {
                        console.error("Error updating status:", error);
                    },
                });
            });
        } else {
            // Handle other statuses
            ConfirmStatus(() => {
                $.ajax({
                    url: "../../api/FetchDataRequest.php",
                    method: "POST",
                    data: { studentObjectID, currentStatus },
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                            fetchRequests(currentStatus);
                        } else {
                            alert("Failed to update status. Please try again.");
                        }
                    },
                    error: function (error) {
                        console.error("Error updating status:", error);
                    },
                });
            });
        }
    });

    function generateStatusIcons(request) {
        const statuses = ["unpaid", "paid", "process", "ready"];
        const icons = {
            unpaid: "../../public/images/icons/warning.png",
            paid: "../../public/images/icons/dollar-sign.png",
            process: "../../public/images/icons/data-processing.png",
            ready: "../../public/images/icons/checked.png",
            done: "../../public/images/icons/done-circle.png",
        };

        return statuses
            .map((status) => {
                let icon;
                if (status === request.status) {
                    icon = icons[status];
                } else if (
                    statuses.indexOf(status) < statuses.indexOf(request.status)
                ) {
                    icon = icons["done"];
                } else {
                    icon = "../../public/images/icons/standby-circle.png";
                }

                return `
                    <div class="reqstatus-name ${status}" data-id="${request._id.$oid
                    }">
                        <img class="icons" src="${icon}" alt="${status} Icon">
                        <p>${status.charAt(0).toUpperCase() + status.slice(1)
                    }</p>
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

    // Helper functions for generating dynamic content
    function escapeHtml(text) {
        return text.replace(/</g, "&lt;").replace(/>/g, "&gt;");
    }

});

function ConfirmStatus(callback) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn-success",
            cancelButton: "btn-danger",
        },
    });
    swalWithBootstrapButtons.fire({
        title: "Are you sure about this confirmation?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Confirm",
        cancelButtonText: "Cancel",
        reverseButtons: true,
    })
        .then((result) => {
            if (result.isConfirmed) {
                swalWithBootstrapButtons.fire({
                    title: "Confirmation Success",
                    text: "Successfully Updated!",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 1500,
                });
                callback();
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            );
        });
}

// Process the selected date and time
function SelectTimeDate(callback) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn-success",
            cancelButton: "btn-danger",
        },
    });

    // First Swal for date and time selection
    swalWithBootstrapButtons.fire({
        title: 'Set the Appointment to \n Pick-Up',
        html: `
            <div class="date-time-container">
                <label for="dateInput">Date:</label>
                <input type="date" id="dateInput" class="swal2-input date" min="${new Date().toISOString().split('T')[0]}" />
                <br>
                <label for="timeInput">Time:</label>
                <input type="time" id="timeInput" class="swal2-input time" />
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: "Confirm",
        cancelButtonText: "Cancel",
        reverseButtons: true,
        preConfirm: () => {
            const date = document.getElementById('dateInput').value;
            const time = document.getElementById('timeInput').value;
            if (!date || !time) {
                swalWithBootstrapButtons.showValidationMessage('Both date and time are required');
                return null;
            }
            return { date, time };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { date, time } = result.value;
            // Confirmation step after selecting date and time
            swalWithBootstrapButtons.fire({
                title: "Are you sure about this confirmation?",
                text: `You selected Date: ${date}, Time: ${time}`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Confirm!",
                cancelButtonText: "Cancel",
                reverseButtons: true,
            }).then((confirmResult) => {
                if (confirmResult.isConfirmed) {
                    swalWithBootstrapButtons.fire({
                        title: "Appointment Setup Success",
                        text: "Successfully Updated!",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                    if (callback) callback(date, time);
                }
            });
        }
    });
}

//TODO: Add an API that sends Email notifications to the Students