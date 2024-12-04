<!DOCTYPE html>

<head>
    <link rel="stylesheet" href="../../public/css/styles.css">
    <style>
        .top-bar {
            background-color: var(--PRIMARY);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 40px;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
            box-shadow: 0 0 10px var(--DARKBG);
        }

        .top-bar h1 {
            font-size: var(--HEADER);
            font-family: var(--POPPINS);
            color: var(--TEXTLIGHT);
        }

        .top-Title {
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        .navigation {
            display: flex;
        }

        .navigation a {
            margin: 0 10vh;
            text-decoration: none;
            font-family: var(--WORK-SANS);
            font-size: var(--SUBHEADER);
            color: var(--TEXTLIGHT);
        }

        .navigation a:hover {
            color: var(--ACCENT);
            font-weight: 600;
        }

        .profile {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
        }

        .account {
            flex-direction: column;
        }

        .account h2 {
            font-size: var(--SUBHEADER);
            font-family: var(--WORK-SANS);
            color: var(--TEXTLIGHT);
            margin: 0;
        }

        .account p {
            font-size: var(--BODY);
            font-family: var(--WORK-SANS);
            color: var(--TEXTLIGHT);
            margin: 0;
        }

        .icon {
            width: 6vh;
            height: 6vh;
            margin-right: 10px;
        }

        .logo {
            width: 8vh;
            height: 8vh;
            margin-right: 5px;
        }

        a {
            text-decoration: none;
        }

        .modal {
            position: absolute;
            top: 60px;
            right: 20px;
            background-color: var(--ACCENT);
            padding: 15px;
            border-radius: 10px;
            display: none;
            /* Hidden by default */
            z-index: 1001;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: opacity 0.3s ease;
            cursor: pointer;
        }
        .modal a{
            color: var(--TEXTDARK);
            font-family: var(--WORK-SANS);
            font-weight: 600;
            font-size: var(--BODY);
        }
    </style>
</head>

<body>
    <div class="top-bar">
        <div class="top-Title">
            <img class="logo" src="../../public/images/icons/logo-white.png" alt="EaseDocu Logo">
            <h1>EaseDocu</h1>
        </div>

        <div class="navigation">
            <a href="../admin/requestList.php">Request</a>
            <a href="../admin/reportsList.php">Report</a>
        </div>

        <div class="profile" id="account">
            <img class="icon" src="../../public/images/icons/profile.png" alt="User Icon">
            <div class="account">
                <!-- TODO: Hovered Logout Panel -->
                <a href="#">
                    <h2>Admin</h2>
                    <p>00-0001</p>
                </a>
            </div>
        </div>
        <div id="modal" class="modal" style="display: none;">
            <a href="logout.php" id="logout-btn">Logout</a>
        </div>
    </div>

    <script>
        //TODO: Add more design such as popup animation
        //TODO: The name and studentID should be based on the database
        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("modal");
            const accountClick = document.getElementById("account");

            accountClick.addEventListener("mouseover", (event) => {
                event.preventDefault();
                modal.style.display = "block";
            });

            modal.addEventListener("mouseover", (event) => {
                event.preventDefault();
                modal.style.display = "block";
            });

            document.addEventListener("mousemove", (event) => {
                if (!modal.contains(event.target) && !accountClick.contains(event.target)) {
                    modal.style.display = "none";
                }
            });

            document.addEventListener("keydown", (event) => {
                if (event.key === "Escape") {
                    modal.style.display = "none";
                }
            });
        });
    </script>
</body>

</html>