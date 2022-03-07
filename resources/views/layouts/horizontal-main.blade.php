@auth

        <!--/Horizontal-main -->
                <div class="sticky">
                    <div class="horizontal-main hor-menu clearfix">
                        <div class="horizontal-mainwrapper container clearfix">
                            <!--Nav-->
                            <nav class="horizontalMenu clearfix">
                                <ul class="horizontalMenu-list">
                                    <li aria-haspopup="true"><a href="{{ url('/' . $page='home') }}" class=""><i class="ti-home"></i> Dashboard</a></li>
                                    <li aria-haspopup="true"><a href="{{ url('/' . $page='sites') }}" class=""><i class="ti-world"></i> My Sites</a></li>
                                    <li aria-haspopup="true"><a href="{{ url('/' . $page='domains') }}" class=""><i class="ti-world"></i> My Domains</a></li>
                                    <li aria-haspopup="true"><a href="{{ url('/' . $page='my_tickets') }}" class=""><i class="ti-world"></i> My Tickets</a></li>
                                    <li aria-haspopup="true"><a href="#" class="sub-icon"><i class="ti-palette"></i>Directory <i class="fa fa-angle-down horizontal-icon"></i></a>
                                        <ul class="sub-menu">
                                            <li aria-haspopup="true"><a href="{{ url('/' . $page='directory/themes/list') }}"> Themes</a></li>
                                            <li aria-haspopup="true"><a href="{{ url('/' . $page='directory/plugins/list') }}">Plugins</a></li>
                                        </ul>
                                    </li>
                                    <li aria-haspopup="true"><a href="https://help.codibu.com/knowledge-base/wordpress_video_tutorials/" class=""><i class="ti-video-clapper"></i> Tutorials</a></li>
                                    <li aria-haspopup="true"><a href="{{ url('/' . $page='faq') }}" class=""><i class="ti-help-alt"></i> FAQ</a></li>
                                    <li aria-haspopup="true"><a href="https://help.codibu.com/" class=""><i class="ti-shield"></i> Help Center</a></li>
                                </ul>
                            </nav>
                            <!--Nav-->
                        </div>
                    </div>
                </div>
                <!--/Horizontal-main -->

@endauth
