            <style>
                html,body{
                    margin: 0px;
                    margin-left: 5px;
                }
                body{
                    background: #252525;
                    font-family: sans-serif;
                    color: #dcdcdc;
                }
                h2{
                    font-size: 28px;
                }
                .text{
                    font-family: cursive;
                    font-size: 22px;
                }
                .container-fluid.fluid.menu-left{
                    display: none;
                }
                .container-idle-dialog{
                    display: none;
                }
                .jsdisabledmsg{
                    /*background-color: #ddd;*/
                    /*color: #000;*/
                    z-index: 900;
                    width: 80%;
                    margin: 0 auto;
                    margin-left: -75px;
                    margin-top: 40%;
                    padding: 15px;
                    padding-top: 0px;
                    border: #ff0 dashed medium;
                    border-radius: 20px;
                    display: block;
                    box-shadow: 0px 0px 10px #fff;
                }
                .imgmaint{
                    z-index: 999;
                    /*background-color: #ddd;*/
                    /*color: #000;*/
                    width: 80%;
                    margin: 0 auto;
                    margin-left: -165px;
                    margin-top: 45%;
                    /*padding: 15px;*/
                    /*border: #ff0 dashed medium;*/
                    /*border-radius: 20px;*/
                    display: block;
                    /*box-shadow: 0px 0px 10px #fff;*/
                }
                .row{
                    
                }
                .h1per1{
                    height: 100%;
                }
                .w1per2{
                    z-index: 99;
                    position: fixed;
                    left: 25%;
                    /*top: 50%;*/
                    /*margin: 0 auto;*/
                    display: inline-block;
                    width   : 50%;
                    height: 90%;
                    /*background: rgba(250,150,150,0.5);*/
                }
                .w1per3{
                    margin: -5px;
                    display: inline-block;
                    width   : 33%;
                    /*background: rgba(150,250,150,0.5);*/
                }
                .w1per4{
                    z-index: 1;
                    position: relative;
                    margin-left: -5px;
                    display: inline-block;
                    width   : 25%;
                    height: 90%;
                    /*background: rgba(250,150,250,0.5);*/
                }
                .garis{
                    position: fixed;
                    top: 0%;
                    left: 0%;
                    width: 100%;
                    height: 1px;
                    background: rgba(255,255,0,1);
                    /*border: #ff0 1px solid;*/
                    /*border-radius: 5px / 50px;*/
                    /*box-shadow: 0px 0px 2px #fff;*/
                }
                
                
.shadowed {
    -webkit-filter: drop-shadow(0px 0px 2px rgba(255,255,255,1));
    filter: url("data:image/svg+xml;utf8,<svg height='0' xmlns='http://www.w3.org/2000/svg'><filter id='drop-shadow'><feGaussianBlur in='SourceAlpha' stdDeviation='5'/><feOffset dx='0' dy='0' result='offsetblur'/><feFlood flood-color='rgba(255,255,255,1)'/><feComposite in2='offsetblur' operator='in'/><feMerge><feMergeNode/><feMergeNode in='SourceGraphic'/></feMerge></filter></svg>#drop-shadow");
    -ms-filter: "progid:DXImageTransform.Microsoft.Dropshadow(OffX=0, OffY=0, Color='#fff')";
    filter: "progid:DXImageTransform.Microsoft.Dropshadow(OffX=0, OffY=0, Color='#fff')";
}           
            </style>
            
            <!--<div class="row">-->
                <div class="w1per4">
                    &nbsp;
                </div>
                <div class="w1per4">
                    &nbsp;
                </div>
                <div class="w1per4">
                    &nbsp;
                </div>
                <div class="w1per4 ">
                    &nbsp;

                    <img class="imgmaint shadowed" src="<?php echo base_url()?>/public/avatar/maintenance.png" >
                </div>     
                <div class="w1per2">
                    <div align='center' class="jsdisabledmsg">
                        <H2>WEB APP IS UNDER MAINTENANCE</h2>
                        <div class="text">
                            We are sorry for the inconvenience!<br>
                            We will back soon and bring all the new for you! :)
                        </div>
                    </div>
                </div>
            <div class="garis"></div>
            <!--</div>-->
