<html>
    <head>
<style>
            @keyframes animate{
                0%
                {
                    transform: translateY(100vh) scale(0);
                }
                100%
                {
                    transform: translateY(-10vh) scale(1);
                }
            }
            .bubbles_1{
                position: fixed;
                display: flex;
                top: 100px;
                left: 0;
            }
            .bubbles_1 span{
                position: relative;
                width: 10px;
                height: 10px;
                background-color: rgb(0, 60, 255);
                margin: 0 4px;
                border-radius: 50%;
                box-shadow: 0 0 0 10px rgb(0, 60, 255),
                0 0 50px rgb(0, 60, 255),
                0 0 100px rgb(0, 60, 255);
                animation: animate 15s linear infinite;
                animation-duration: calc(100s / var(--i));
            }
            .bubbles_1 span:nth-child(even){
                background: #16158c;
                box-shadow: 0 0 0 10px #16158c,
                0 0 50px #16158c,
                0 0 100px #16158c;
            }
</style>
    </head>
<body>
    <div class="container">
        <div class="side">
            <div class="bubbles_1">
                <span style="--i:11;"></span>
                <span style="--i:7;"></span>
                <span style="--i:15;"></span>
                <span style="--i:12;"></span>
                <span style="--i:3;"></span>
                <span style="--i:26;"></span>
                <span style="--i:14;"></span>
                <span style="--i:10;"></span>
                <span style="--i:19;"></span>
                <span style="--i:5;"></span>
                <span style="--i:13;"></span>
                <span style="--i:23;"></span>
                <span style="--i:9;"></span>
            </div>
        </div>
    </div>
</body>
</html>