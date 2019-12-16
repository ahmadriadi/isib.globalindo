<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Outlook Request</title>
</head>
    <style media="screen" type="text/css">
        /* <!-- */
        /* General styles */
        body {
            margin:0;
            padding:0;
            border:0;			/* This removes the border around the viewport in old versions of IE */
            width:90%;
            background:#fff;
            min-width:600px;		/* Minimum width of layout - remove line if not required */
            /* The min-width property does not work in old versions of Internet Explorer */
            font-size:90%;
        }
        a {
            color:#369;
        }
        a:hover {
            color:#fff;
            background:#369;
            text-decoration:none;
        }
        h1, h2, h3 {
            margin:.8em 0 .2em 0;
            padding:0;
        }
        p {
            margin:.4em 0 .8em 0;
            padding:0;
        }
        img {
            margin:10px 0 5px;
        }
        /* Header styles */
        #header {
            clear:both;
            float:left;
            width:100%;
        }
        #header {
            border-bottom:1px ; /* solid #000;*/
        }
        #header p,
        #header h1,
        #header h2 {
            padding:.4em 15px 0 15px;
            margin:0;
        }
        #header ul {
            clear:left;
            float:left;
            width:100%;
            list-style:none;
            margin:10px 0 0 0;
            padding:0;
        }
        #header ul li {
            display:inline;
            list-style:none;
            margin:0;
            padding:0;
        }
        #header ul li a {
            display:block;
            float:left;
            margin:0 0 0 1px;
            padding:3px 10px;
            text-align:center;
            background:#eee;
            color:#000;
            text-decoration:none;
            position:relative;
            left:15px;
            line-height:1.3em;
        }
        #header ul li a:hover {
            background:#369;
            color:#fff;
        }
        #header ul li a.active,
        #header ul li a.active:hover {
            color:#fff;
            background:#000;
            font-weight:bold;
        }
        #header ul li a span {
            display:block;
        }
        /* 'widths' sub menu */
        #layoutdims {
            clear:both;
            background:#eee;
            border-top:4px solid #000;
            margin:0;
            padding:6px 15px !important;
            text-align:right;
        }
        /* column container */
        .colmask {
            position:relative;	/* This fixes the IE7 overflow hidden bug */
            clear:both;
            float:left;
            width:100%;			/* width of whole page */
            overflow:hidden;		/* This chops off any overhanging divs */
        }
        /* common column settings */
        .colright,
        .colmid,
        .colleft {
            float:left;
            width:100%;
            position:relative;
        }
        .col1,
        .col2,
        .col3 {
            float:left;
            position:relative;
            padding:0 0 1em 0;
            overflow:hidden;
        }
        /* Full page settings */
        .fullpage {
            background:#fff;		/* page background colour */
        }
        .fullpage .col1 {
            width:100%;			/* page width minus left and right padding */
            left:4%;			/* page left padding */
        }
        /* Footer styles */
        #footer {
            clear:both;
            float:left;
            width:100%;
            border-top:1px; /*solid #000;*/
        }
        #footer p {
            padding:10px;
            margin:0;
        }

        table {
            width: 100%;
            border: 1px solid #cef;
            text-align: left; }
        th {
            font-weight: bold;
            background-color: #acf;
            border-bottom: 1px solid #cef; }
        td,th {
            padding: 4px 5px; }

        .fontheader {
            color: #FFFFFF;
            font-weight: bold;
        }

        /* --> */
    </style>
    <body>
        <div class="colmask fullpage"> 
            <div class="col1">
                <BR />
                <p>
                    Dear <b> Divisi IT</b>,
                </p><BR />

                
                
                <p><?php echo $note; ?></p>
                
                <p>
                    Regards,<BR /><BR />
                </p>
                <P><FONT COLOR="#1f497d"><FONT FACE="Times New Roman"><FONT SIZE=2 STYLE="font-size: 14pt"><B><small><?php echo $name.'</small><br/>'.$deptuser ?></B></FONT></FONT></FONT></P>
                <P><FONT COLOR="#1f497d"><FONT FACE="Times New Roman"><FONT SIZE=1 STYLE="font-size: 7pt">
                                <B></B></FONT></FONT></FONT><FONT COLOR="#1f497d"><FONT FACE="Times New Roman"><FONT SIZE=1 STYLE="font-size: 7pt"><B>
                                     </B></FONT></FONT></FONT><FONT COLOR="#1f497d"><FONT FACE="Times New Roman"><B>Email:</B></FONT></FONT><FONT COLOR="#1f497d"><FONT FACE="Times New Roman"><FONT SIZE=1 STYLE="font-size: 7pt"><B>
                                </B></FONT></FONT></FONT><A HREF="mailto:<?php echo $emailuser  ?>"><FONT COLOR="#1f497d"><FONT FACE="Times New Roman">
                                <B><?php echo $emailuser ?></B></FONT></FONT>
                    </A><FONT COLOR="#1f497d"><FONT FACE="Times New Roman"><FONT SIZE=1 STYLE="font-size: 7pt"><B>;
                                    | Mobile</B></FONT></FONT></FONT><FONT COLOR="#1f497d"><FONT FACE="Times New Roman"><FONT SIZE=1 STYLE="font-size: 7pt">:
                            </FONT></FONT></FONT><FONT COLOR="#1f497d"><FONT FACE="Times New Roman"><FONT SIZE=1 STYLE="font-size: 7pt"><?php echo $nohp ?></FONT></FONT></FONT><FONT COLOR="#1f497d"><FONT FACE="Times New Roman"><FONT SIZE=1 STYLE="font-size: 7pt">
                            </FONT></FONT></FONT><FONT COLOR="#1f497d"><FONT FACE="Times New Roman"><FONT SIZE=1 STYLE="font-size: 7pt">
                            </FONT></FONT></FONT></P>
                <P><FONT COLOR="#280ec4"> </FONT>
                </P>
                <P><FONT COLOR="#280ec4"><FONT FACE="Times New Roman"><FONT SIZE=3 STYLE="font-size: 16pt"><B>PT.
                                    TRIAS INDRA SAPUTRA</B></FONT></FONT></FONT></P>
                <P><FONT COLOR="#1f497d"><FONT FACE="Times New Roman"><FONT SIZE=1 STYLE="font-size: 7pt"><B>Phone</B></FONT></FONT></FONT><FONT COLOR="#1f497d"><FONT FACE="Times New Roman"><FONT SIZE=1 STYLE="font-size: 7pt">:
                                +62 21 555 2989 | </FONT></FONT></FONT><FONT COLOR="#1f497d"><FONT FACE="Times New Roman"><FONT SIZE=1 STYLE="font-size: 7pt"><B>Fax</B></FONT></FONT></FONT><FONT COLOR="#1f497d"><FONT FACE="Times New Roman"><FONT SIZE=1 STYLE="font-size: 7pt">:
                                +62 21 619 8571/5812</FONT></FONT></FONT><FONT COLOR="#1f497d"><FONT FACE="Times New Roman"><FONT SIZE=1 STYLE="font-size: 7pt"><B><BR>Address</B></FONT></FONT></FONT><FONT COLOR="#1f497d"><FONT FACE="Times New Roman"><FONT SIZE=1 STYLE="font-size: 7pt">:
                                Sentra Industri Pantai Indah Kapuk, Jl. Dr. Kamal Muara VII Blok A
                                No.6, Jakarta Utara 14470</FONT></FONT></FONT><FONT COLOR="#1f497d"><FONT FACE="Times New Roman"><FONT SIZE=1 STYLE="font-size: 7pt"><B><BR>
                                        Email</B></FONT></FONT></FONT><FONT COLOR="#1f497d"><FONT FACE="Times New Roman"><FONT SIZE=1 STYLE="font-size: 7pt">:
                                <A HREF="mailto:info@triasindrasaputra.com">info@triasindrasaputra.com</A>;
                                | </FONT></FONT></FONT><FONT COLOR="#1f497d"><FONT FACE="Times New Roman"><FONT SIZE=1 STYLE="font-size: 7pt">
                                <B>Website</B></FONT></FONT></FONT><FONT COLOR="#1f497d"><FONT FACE="Times New Roman"><FONT SIZE=1 STYLE="font-size: 7pt">
                                : <A HREF="http://www.triasindrasaputra.com/" TARGET="_blank">www.Trias
                                    IndraSaputra.com</A></FONT></FONT></FONT></P>
                <P><FONT COLOR="#1f497d">“<FONT FACE="Times New Roman"><FONT SIZE=1 STYLE="font-size: 8pt"><I>Melindungi
                                    Investasi Anda dengan Pelayanan dan Solusi Energi yang Tepat dan
                                    Terpercaya”</I></FONT></FONT></FONT></P>
                <P><FONT COLOR="#1f497d"><FONT FACE="Times New Roman"><FONT SIZE=1 STYLE="font-size: 5pt"><I>The
                                    information contained in this email message (including any
                                    attachments) is privileged and/or confidential and is intended only
                                    for the use of the individual or entity named above.If you are not
                                    the intended recipient, you are hereby notified <br />that any
                                    dissemination, distribution or copying of this communication is
                                    strictly prohibited. PT. Trias Indra Saputra does not guarantee that
                                    the integrity of this communication has been maintained or that this
                                    communication is free of viruses, <br />interceptions or interference. If
                                    you have received this communication in error, please destroy the
                                    message and notify us immediately by reply e-mail and immediately
                                    delete this e-mail and any of its attachments.</I></FONT></FONT></FONT></P>
            </div>
        </div>

    </body>
</html>


