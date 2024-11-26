$(document).ready(function () {
    // Attach a click event listener to all buttons with class 'confirm-btn'
    $('.confirm-btn').on('click', function (event) {
        event.preventDefault(); // Prevent form submission

        // Get the form and input values from the parent form
        const form = $(this).closest('#status-update-form');
        const studentID = form.find('input[name="studentID"]').val();
        const currentStatus = form.find('input[name="currentStatus"]').val();

        // Make an AJAX POST request
        $.ajax({
            url: '../../controller/FetchDataRequest.php',
            type: 'POST',
            data: {
                studentID: studentID,
                currentStatus: currentStatus
            },
            success: function (data) {
                const responseData = JSON.parse(data);
                if (responseData.success) {
                    // Update the status in the DOM if the request was successful
                    const statusText = form.closest('.status-details').find('.status-text');
                    statusText.text(`Request Status: ${responseData.newStatus}`);

                    // Update the hidden input field for 'currentStatus'
                    form.find('input[name="currentStatus"]').val(responseData.newStatus);

                    //TODO: Update Status Icon Here (class - .reqstatus-name)
                    // const statusIcon = $(`.reqstatus-name.${responseData.newStatus} img`);
                    // statusIcon.attr('src', getNextIconStatus(responseData.newStatus));
                    // Assuming responseData contains the updated `studentID` and `newStatus` from the backend
                    updateStatusIcon(responseData.studentID, responseData.newStatus);

                    // Update the button text
                    const confirmBtn = form.find('.confirm-btn');
                    confirmBtn.text(getNextButtonText(responseData.newStatus));

                    // Hide form if status is 'ready'
                    if (responseData.newStatus === 'ready') {
                        form.hide();
                    }
                } else {
                    alert('Failed to update status. Please try again.');
                }
            },
            error: function (error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            }
        });
    });
});

// Helper function to determine the next button text based on the current status
function getNextButtonText(status) {
    if (status === 'unpaid') return 'Confirm Payment';
    if (status === 'paid') return 'Confirm to Process';
    if (status === 'process') return 'Confirm Finished';
    return null;
}

const icons = {
    unpaid: "../../public/images/icons/warning.png",
    paid: "../../public/images/icons/dollar-sign.png",
    process: "../../public/images/icons/data-processing.png",
    ready: "../../public/images/icons/checked.png",
    standby: "../../public/images/icons/standby-circle.png",
    done: "../../public/images/icons/done-circle.png" // Add any additional icons as needed
};

// Function to determine the correct icon based on the current status
function getUpdatedIcons(status) {
    const updatedIcons = {
        unpaid: status === "unpaid" ? icons.unpaid : icons.done,
        paid: status === "paid" ? icons.paid : icons.done,
        process: status === "process" ? icons.process : icons.done,
        ready: status === "ready" ? icons.ready : icons.done
    };

    return updatedIcons;
}

function updateStatusIcon(studentID, newStatus) {
    // Find the specific status icon for the given studentID
    const statusIcon = $(`.reqstatus-name[data-student-id="${studentID}"] img`);

    // If the icon exists, update its `src` attribute with the new icon
    if (statusIcon.length) {
        const updatedIcons = getUpdatedIcons(newStatus); // Get updated icons based on the new status
        statusIcon.attr('src', updatedIcons[newStatus]); // Update the icon
    }
}


// Select all filter items
document.querySelectorAll('.filters nav ul li').forEach(item => {
    item.addEventListener('click', function () {
        document.querySelectorAll('.filters nav ul li').forEach(li => li.classList.remove('active'));
        // Add active class to the clicked item
        item.classList.add('active');
    });
    // Initialization active for unpaid
    if (item.textContent.includes('UNPAID')) {
        item.classList.add('active');
    }
});

document.addEventListener('DOMContentLoaded', () => {
    // Select all data rows
    const dataRows = document.querySelectorAll('.data-row');
    dataRows.forEach(row => {
        const requestId = row.getAttribute('data-id'); // Get the request ID
        const confirmationRow = document.getElementById(`confirmation-${requestId}`);

        // Add click event to toggle confirmation row
        row.addEventListener('click', () => {
            const confirmationRows = document.querySelectorAll('.confirmation-status');
            confirmationRows.forEach(row => {
                if (row !== confirmationRow && row.classList.contains('show')) {
                    row.classList.remove('show');
                    row.classList.add('hide');
                    setTimeout(() => {
                        row.style.display = 'none';
                        row.classList.remove('hide');
                    }, 500);
                }
            });
            if (confirmationRow.classList.contains('show')) {
                confirmationRow.classList.remove('show');
                confirmationRow.classList.add('hide');
                setTimeout(() => {
                    confirmationRow.style.display = 'none';
                    confirmationRow.classList.remove('hide');
                }, 500);
                // console.log('Confirmation status hidden:', requestId);
            } else {
                confirmationRow.style.display = 'table-row';
                confirmationRow.classList.add('show');
                // console.log('Confirmation status shown:', requestId);
            }
        });
    });
});