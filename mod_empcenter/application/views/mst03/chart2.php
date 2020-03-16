
}
		};

		//////////////////////////////////////////////////////////////////////////
		// UP TO HERE ARE THE STRUCTURE OF NODES AND HIERARCHIES FOR THE ORG CHART
		// FROM HERE YOU WILL FIND HOW TO DEFINE RENDERING AND LOOK AND FEEL
		//////////////////////////////////////////////////////////////////////////



		// standard box click handler; you can change this
		// used for both first and second forms of invocation of or chart rendering
		//
		function klik_box (event, box) {
				alert(box.oc_id);
		}



		// FIRST FORM OF CALLING THE RENDERING METHOD
		// define the org chart styles
		//
		var use_images = oc_data_2;
		var oc_options1 = {
            container: 'oc_container1',              // name of the DIV where the chart will be drawn
            vline: 10,                               // size of the smallest vertical line of connectors
            hline: 10,                               // size of the smallest horizontal line of connectors
            xoffset: 0,                              // inital x-offset of diagram (can be negative)
            yoffset: 0,                              // inital y-offset of diagram (can be negative)
            inner_padding: 10,                       // space from text to box border
            box_color: '#D9EDF7',                    // fill color of boxes
            box_color_hover: '#E9FDF7',              // fill color of boxes when mouse is over them
            box_border_color: '#BCE8F1',             // stroke color of boxes
            box_border_radius: 8,                    // border radius of boxes in pixels
            box_border_width: 2,                     // border with of boxes in pixels
            box_fix_width: null,                     // set fix width for boxes in pixels
            box_fix_height: null,                    // set fix height for boxes in pixels
            box_root_node_width: null,               // override fix width and max text width
            box_root_node_height: null,              // override fix height and size defined by text length
            box_html_template: 'oc_template',        // id of element with template; Depends on jsrender and jQuery libraries!
            line_color: '#3A87AD',                   // color of connectors
            title_color: '#3A87AD',                  // color of titles
            subtitle_color: '#1A678D',               // color of subtitles
            title_font_size: 12,                     // size of font used for displaying titles inside boxes
            subtitle_font_size: 10,                  // size of font used for displaying subtitles inside boxes
            title_char_size: [6, 12],                // size (x, y) of a char of the font used for displaying titles
            subtitle_char_size: [5, 10],             // size (x, y) of a char of the font used for displaying subtitles
            max_text_width: 15,                      // max width (in chars) of each line of text ('0' for no limit)
            text_font: 'arial',                    // font family to use (should be monospaced)
            use_images: use_images,                  // use images within boxes?
            images_base_url: './images/',            // base url of the images to be embeeded in boxes, with a trailing slash
            images_size: [60, 60],                 // size (x, y) of the images to be embeeded in boxes
            box_click_handler: klik_box, // handler (function) called on click on boxes (set to null if no handler)
            debug: false                             // set to true if you want to debug the library
		};
		//
		// the following style block is also used by the FIRST FORM of rendering method; you can customize it
		//
		</script>
	    <style>
                <?php $base_url = $this->session->userdata('sess_base_url'); ?>
		    .node              { padding-top: 4px; text-align: center; font-size:13px; }
		    .node:first-letter { font-weight: bold; }
	    </style>
	    <!---->
		<!-- optional JS templating mechanism for the FIRST FORM of rendering invocation -->
		<!-- TO_DO this needs documentation -->
	    <!---->

		<script type="text/javascript">
		//
		// now let the library call function 'render()' when you are ready to draw the chart
		// chart will be rendered into a DIV with id = 'oc_container' (or as specified in oc_options)
		//
		window.onload = function() { ggOrgChart.render(oc_data_2, oc_options2); };
		//
		// END OF FIRST FORM OF CALLING THE RENDERING METHOD



		// SECOND FORM OF CALLING THE RENDERING METHOD
		// define the org chart styles
		//
		var oc_options2 = {
			container          : 'oc_container',
                        vline              : 10,                               // size of the smallest vertical line of connectors
                        hline              : 10,                               // size of the smallest horizontal line of connectors			box_color          : '#aaf',                 // fill color of boxes
                        text_font          : '',
                        box_color_hover    : '#faa',                 // fill color of boxes when mouse is over them
			box_border_color   : '#008',                 // stroke color of boxes
			line_color         : '#f44',                 // color of connectors
			title_color        : '#000',                 // color of titles
			subtitle_color     : '#707',                 // color of subtitles
			use_images         : use_images,             // use images within boxes?
                        images_base_url    : '<?php echo $base_url;?>/public/avatar/photo/',            // base url of the images to be embeeded in boxes, with a trailing slash
                        images_size        : [60, 60], 			
                        box_click_handler  : klik_box,   // handler (function) called on click on boxes (set to null if no handler)
			debug              : false                   // set to true if you want to debug the library
		};
		// look below inside the DIV tag in the BODY ('oc_container' or as defined before)
		// for how to invoke the rendering using the SECOND FORM of rendering invocation
	</script>

</head>  
<body>
    	    <script id="oc_template" type="text/x-jsrender">
	        <div class="node">
	            <div>{{>title}}</div>
	            <!--div>{{>title}}<br/>{{>subtitle}}</div-->
	        </div>
	    </script>
    <form>
    <input type="button" value="Print this page" onClick="window.print()">
    </form>
    <h3 align="center">GLOBALINDO</h3>
    <p align="center">Organization Chart</p>
    <div align="center" id="oc_container"></div>
</body>
</html>