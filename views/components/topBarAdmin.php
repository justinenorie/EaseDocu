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

        .account :hover {
            color: var(--ACCENT);
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

        <div class="profile">
            <img class="icon" src="../../public/images/icons/profile.png" alt="User Icon">
            <div class="account">
                <!-- TODO: Hovered Logout Panel -->
                <a href="#">
                    <h2>Admin</h2>
                    <p>00-0001</p>
                </a>
            </div>
        </div>
    </div>
</body>

</html>