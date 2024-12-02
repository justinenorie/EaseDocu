
<!DOCTYPE html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../public/css/styles.css">
    <style>
        body {
            margin: 0;
        }

        .top-bar {
            background-color: var(--PRIMARY);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 60px;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
            box-shadow: 0 0 10px var(--DARKBG);
            z-index: 1000;
        }

        .top-bar h1 {
            margin: 0;
            font-size: var(--HEADER);
            font-family: var(--POPPINS);
            color: var(--TEXTLIGHT);
        }

        .title {
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        .profile {
            /* background-color: red; */
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 20px;
            height: 40px;
        }

        .account {
            /* background-color: violet; */
            height: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .account a {
            display: flex;
            justify-content: space-between;
            align-items: start;
            flex-direction: column;
        }

        .account h2 {
            font-size: 20px;
            font-family: var(--WORK-SANS);
            color: var(--TEXTLIGHT);
            margin: 0;
        }

        .account p {
            margin: 0;
            margin-top: 2px;
            font-size: 16px;
            font-family: var(--WORK-SANS);
            color: var(--TEXTLIGHT);
        }

        .icon {
            /* background-color: green; */
            width: 6vh;
            height: 6vh;
            margin-right: 10px;
        }

        .logo {
            width: 40px;
            height: 40px;
            margin-right: 12px;
        }

        a {
            text-decoration: none;
        }

        .modal {
            position: absolute;
            top: 70px;
            right: 20px;
            background-color: blue;
            padding: 15px;
            color: var(--TEXTLIGHT);
            border-radius: 10px;
            display: none;
            /* Hidden by default */
            z-index: 1001;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: opacity 0.3s ease;
        }

        /* Show the modal
        .modal.show {
            display: block;
            opacity: 1;
        } */

        @media screen and (max-width: 550px) {
            .top-bar {
                padding: 20px 35px;
            }

            .profile {
                padding-right: 0;
            }
        }

        @media screen and (max-width: 498px) {
            .title h1 {
                display: none;
            }

            .top-bar {
                padding: 20px 30px;
            }

            .account p {
                display: none;
            }

            .account h2 {
                margin: 0;
            }

            .account {
                justify-content: center;
            }

            .profile {
                align-items: center;
            }
        }
    </style>
</head>

<body>
    <div class="top-bar">
        <div class="title">
            <img class="logo" src="../../public/images/icons/logo-white.png" alt="EaseDocu Logo">
            <h1>EaseDocu</h1>
        </div>

        <div class="profile">
            <img class="icon" src="../../public/images/icons/profile.png" alt="User Icon">

            <div class="account" id="account">
                <a id="click-profile" href="#">
                <h2><?php echo htmlspecialchars($userEmail ?? 'Unknown User'); ?></h2>
<p>22-00000 </p>

                </a>
            </div>
        </div>
    </div>

    <div id="modal" class="modal" style="display: none;">
        <p style="cursor: pointer;color:white;" >Show Profile</p>
        <a style="cursor: pointer;color:white;" href="logout.php" id="logout-btn">Logout</a>
    </div>
    <script>
            //TODO: Add more design such as popup animation
        //TODO: The name and studentID should be based on the database
        document.addEventListener("DOMContentLoaded", () => {
            const modal = document.getElementById("modal");
            const accountClick = document.getElementById("account");
        
            accountClick.addEventListener("click", (event) => {
                event.preventDefault();
                modal.style.display = "block";
            });

            document.addEventListener("click", (event) => {
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