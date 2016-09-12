




<!DOCTYPE html>
<html>
  <head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# object: http://ogp.me/ns/object# article: http://ogp.me/ns/article# profile: http://ogp.me/ns/profile#">
    <meta charset='utf-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>sliderTabs/jquery.sliderTabs.js at master · lopatin/sliderTabs · GitHub</title>
    <link rel="search" type="application/opensearchdescription+xml" href="/opensearch.xml" title="GitHub" />
    <link rel="fluid-icon" href="https://github.com/fluidicon.png" title="GitHub" />
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-114.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-144.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144.png" />
    <meta property="fb:app_id" content="1401488693436528"/>

      <meta content="@github" name="twitter:site" /><meta content="summary" name="twitter:card" /><meta content="lopatin/sliderTabs" name="twitter:title" /><meta content="sliderTabs - SliderTabs - A flexible, super simple jQuery plugin for sliding tabs." name="twitter:description" /><meta content="https://1.gravatar.com/avatar/b3fd692654b29d15010cf8ea793ef8ca?d=https%3A%2F%2Fidenticons.github.com%2Feed21c184b4302bb89fbb4dbb4da33c0.png&amp;r=x&amp;s=400" name="twitter:image:src" />
<meta content="GitHub" property="og:site_name" /><meta content="object" property="og:type" /><meta content="https://1.gravatar.com/avatar/b3fd692654b29d15010cf8ea793ef8ca?d=https%3A%2F%2Fidenticons.github.com%2Feed21c184b4302bb89fbb4dbb4da33c0.png&amp;r=x&amp;s=400" property="og:image" /><meta content="lopatin/sliderTabs" property="og:title" /><meta content="https://github.com/lopatin/sliderTabs" property="og:url" /><meta content="sliderTabs - SliderTabs - A flexible, super simple jQuery plugin for sliding tabs." property="og:description" />

    <meta name="hostname" content="github-fe139-cp1-prd.iad.github.net">
    <meta name="ruby" content="ruby 2.1.0p0-github-tcmalloc (87c9373a41) [x86_64-linux]">
    <link rel="assets" href="https://github.global.ssl.fastly.net/">
    <link rel="conduit-xhr" href="https://ghconduit.com:25035/">
    <link rel="xhr-socket" href="/_sockets" />


    <meta name="msapplication-TileImage" content="/windows-tile.png" />
    <meta name="msapplication-TileColor" content="#ffffff" />
    <meta name="selected-link" value="repo_source" data-pjax-transient />
    <meta content="collector.githubapp.com" name="octolytics-host" /><meta content="collector-cdn.github.com" name="octolytics-script-host" /><meta content="github" name="octolytics-app-id" /><meta content="CC59FBEF:78C3:B43224:5308B4E1" name="octolytics-dimension-request_id" />
    

    
    
    <link rel="icon" type="image/x-icon" href="/favicon.ico" />

    <meta content="authenticity_token" name="csrf-param" />
<meta content="tEVvxr2F4uG8jEQdEeEKHhKijFKQ784kcZOfwmUODXQ=" name="csrf-token" />

    <link href="https://github.global.ssl.fastly.net/assets/github-22cc6aa8138609ccbf0c65025e153af581662ef6.css" media="all" rel="stylesheet" type="text/css" />
    <link href="https://github.global.ssl.fastly.net/assets/github2-dd234c178c0a2e0769bab2b5c636ce8f3fc1f02a.css" media="all" rel="stylesheet" type="text/css" />
    
    


      <script crossorigin="anonymous" src="https://github.global.ssl.fastly.net/assets/frameworks-01ab94ef47d6293597922a1fab60e274e1d8f37e.js" type="text/javascript"></script>
      <script async="async" crossorigin="anonymous" src="https://github.global.ssl.fastly.net/assets/github-a8a26802e0e7283b39ee4507af78950399f2a5d1.js" type="text/javascript"></script>
      
      <meta http-equiv="x-pjax-version" content="df411d3c97b15f5ab1e253f83d14f069">

        <link data-pjax-transient rel='permalink' href='/lopatin/sliderTabs/blob/055a77f6f6d03f226cf98875101fcfd3d70b2c1b/jquery.sliderTabs.js'>

  <meta name="description" content="sliderTabs - SliderTabs - A flexible, super simple jQuery plugin for sliding tabs." />

  <meta content="1187758" name="octolytics-dimension-user_id" /><meta content="lopatin" name="octolytics-dimension-user_login" /><meta content="3287138" name="octolytics-dimension-repository_id" /><meta content="lopatin/sliderTabs" name="octolytics-dimension-repository_nwo" /><meta content="true" name="octolytics-dimension-repository_public" /><meta content="false" name="octolytics-dimension-repository_is_fork" /><meta content="3287138" name="octolytics-dimension-repository_network_root_id" /><meta content="lopatin/sliderTabs" name="octolytics-dimension-repository_network_root_nwo" />
  <link href="https://github.com/lopatin/sliderTabs/commits/master.atom" rel="alternate" title="Recent Commits to sliderTabs:master" type="application/atom+xml" />

  </head>


  <body class="logged_out  env-production  vis-public page-blob tipsy-tooltips">
    <div class="wrapper">
      
      
      
      


      
      <div class="header header-logged-out">
  <div class="container clearfix">

    <a class="header-logo-wordmark" href="https://github.com/">
      <span class="mega-octicon octicon-logo-github"></span>
    </a>

    <div class="header-actions">
        <a class="button primary" href="/join">Sign up</a>
      <a class="button signin" href="/login?return_to=%2Flopatin%2FsliderTabs%2Fblob%2Fmaster%2Fjquery.sliderTabs.js">Sign in</a>
    </div>

    <div class="command-bar js-command-bar  in-repository">

      <ul class="top-nav">
          <li class="explore"><a href="/explore">Explore</a></li>
        <li class="features"><a href="/features">Features</a></li>
          <li class="enterprise"><a href="https://enterprise.github.com/">Enterprise</a></li>
          <li class="blog"><a href="/blog">Blog</a></li>
      </ul>
        <form accept-charset="UTF-8" action="/search" class="command-bar-form" id="top_search_form" method="get">

<input type="text" data-hotkey="/ s" name="q" id="js-command-bar-field" placeholder="Search or type a command" tabindex="1" autocapitalize="off"
    
    
      data-repo="lopatin/sliderTabs"
      data-branch="master"
      data-sha="d0d0344b97a06a9c6514c89038eacc9e036b0baf"
  >

    <input type="hidden" name="nwo" value="lopatin/sliderTabs" />

    <div class="select-menu js-menu-container js-select-menu search-context-select-menu">
      <span class="minibutton select-menu-button js-menu-target" role="button" aria-haspopup="true">
        <span class="js-select-button">This repository</span>
      </span>

      <div class="select-menu-modal-holder js-menu-content js-navigation-container" aria-hidden="true">
        <div class="select-menu-modal">

          <div class="select-menu-item js-navigation-item js-this-repository-navigation-item selected">
            <span class="select-menu-item-icon octicon octicon-check"></span>
            <input type="radio" class="js-search-this-repository" name="search_target" value="repository" checked="checked" />
            <div class="select-menu-item-text js-select-button-text">This repository</div>
          </div> <!-- /.select-menu-item -->

          <div class="select-menu-item js-navigation-item js-all-repositories-navigation-item">
            <span class="select-menu-item-icon octicon octicon-check"></span>
            <input type="radio" name="search_target" value="global" />
            <div class="select-menu-item-text js-select-button-text">All repositories</div>
          </div> <!-- /.select-menu-item -->

        </div>
      </div>
    </div>

  <span class="help tooltipped tooltipped-s" aria-label="Show command bar help">
    <span class="octicon octicon-question"></span>
  </span>


  <input type="hidden" name="ref" value="cmdform">

</form>
    </div>

  </div>
</div>




          <div class="site" itemscope itemtype="http://schema.org/WebPage">
    
    <div class="pagehead repohead instapaper_ignore readability-menu">
      <div class="container">
        

<ul class="pagehead-actions">


  <li>
    <a href="/login?return_to=%2Flopatin%2FsliderTabs"
    class="minibutton with-count js-toggler-target star-button tooltipped tooltipped-n"
    aria-label="You must be signed in to use this feature" rel="nofollow">
    <span class="octicon octicon-star"></span>Star
  </a>

    <a class="social-count js-social-count" href="/lopatin/sliderTabs/stargazers">
      19
    </a>

  </li>

    <li>
      <a href="/login?return_to=%2Flopatin%2FsliderTabs"
        class="minibutton with-count js-toggler-target fork-button tooltipped tooltipped-n"
        aria-label="You must be signed in to fork a repository" rel="nofollow">
        <span class="octicon octicon-git-branch"></span>Fork
      </a>
      <a href="/lopatin/sliderTabs/network" class="social-count">
        21
      </a>
    </li>
</ul>

        <h1 itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="entry-title public">
          <span class="repo-label"><span>public</span></span>
          <span class="mega-octicon octicon-repo"></span>
          <span class="author">
            <a href="/lopatin" class="url fn" itemprop="url" rel="author"><span itemprop="title">lopatin</span></a>
          </span>
          <span class="repohead-name-divider">/</span>
          <strong><a href="/lopatin/sliderTabs" class="js-current-repository js-repo-home-link">sliderTabs</a></strong>

          <span class="page-context-loader">
            <img alt="Octocat-spinner-32" height="16" src="https://github.global.ssl.fastly.net/images/spinners/octocat-spinner-32.gif" width="16" />
          </span>

        </h1>
      </div><!-- /.container -->
    </div><!-- /.repohead -->

    <div class="container">
      <div class="repository-with-sidebar repo-container new-discussion-timeline js-new-discussion-timeline  ">
        <div class="repository-sidebar clearfix">
            

<div class="sunken-menu vertical-right repo-nav js-repo-nav js-repository-container-pjax js-octicon-loaders">
  <div class="sunken-menu-contents">
    <ul class="sunken-menu-group">
      <li class="tooltipped tooltipped-w" aria-label="Code">
        <a href="/lopatin/sliderTabs" aria-label="Code" class="selected js-selected-navigation-item sunken-menu-item" data-gotokey="c" data-pjax="true" data-selected-links="repo_source repo_downloads repo_commits repo_tags repo_branches /lopatin/sliderTabs">
          <span class="octicon octicon-code"></span> <span class="full-word">Code</span>
          <img alt="Octocat-spinner-32" class="mini-loader" height="16" src="https://github.global.ssl.fastly.net/images/spinners/octocat-spinner-32.gif" width="16" />
</a>      </li>


      <li class="tooltipped tooltipped-w" aria-label="Pull Requests">
        <a href="/lopatin/sliderTabs/pulls" aria-label="Pull Requests" class="js-selected-navigation-item sunken-menu-item js-disable-pjax" data-gotokey="p" data-selected-links="repo_pulls /lopatin/sliderTabs/pulls">
            <span class="octicon octicon-git-pull-request"></span> <span class="full-word">Pull Requests</span>
            <span class='counter'>2</span>
            <img alt="Octocat-spinner-32" class="mini-loader" height="16" src="https://github.global.ssl.fastly.net/images/spinners/octocat-spinner-32.gif" width="16" />
</a>      </li>


    </ul>
    <div class="sunken-menu-separator"></div>
    <ul class="sunken-menu-group">

      <li class="tooltipped tooltipped-w" aria-label="Pulse">
        <a href="/lopatin/sliderTabs/pulse" aria-label="Pulse" class="js-selected-navigation-item sunken-menu-item" data-pjax="true" data-selected-links="pulse /lopatin/sliderTabs/pulse">
          <span class="octicon octicon-pulse"></span> <span class="full-word">Pulse</span>
          <img alt="Octocat-spinner-32" class="mini-loader" height="16" src="https://github.global.ssl.fastly.net/images/spinners/octocat-spinner-32.gif" width="16" />
</a>      </li>

      <li class="tooltipped tooltipped-w" aria-label="Graphs">
        <a href="/lopatin/sliderTabs/graphs" aria-label="Graphs" class="js-selected-navigation-item sunken-menu-item" data-pjax="true" data-selected-links="repo_graphs repo_contributors /lopatin/sliderTabs/graphs">
          <span class="octicon octicon-graph"></span> <span class="full-word">Graphs</span>
          <img alt="Octocat-spinner-32" class="mini-loader" height="16" src="https://github.global.ssl.fastly.net/images/spinners/octocat-spinner-32.gif" width="16" />
</a>      </li>

      <li class="tooltipped tooltipped-w" aria-label="Network">
        <a href="/lopatin/sliderTabs/network" aria-label="Network" class="js-selected-navigation-item sunken-menu-item js-disable-pjax" data-selected-links="repo_network /lopatin/sliderTabs/network">
          <span class="octicon octicon-git-branch"></span> <span class="full-word">Network</span>
          <img alt="Octocat-spinner-32" class="mini-loader" height="16" src="https://github.global.ssl.fastly.net/images/spinners/octocat-spinner-32.gif" width="16" />
</a>      </li>
    </ul>


  </div>
</div>

              <div class="only-with-full-nav">
                

  

<div class="clone-url open"
  data-protocol-type="http"
  data-url="/users/set_protocol?protocol_selector=http&amp;protocol_type=clone">
  <h3><strong>HTTPS</strong> clone URL</h3>
  <div class="clone-url-box">
    <input type="text" class="clone js-url-field"
           value="https://github.com/lopatin/sliderTabs.git" readonly="readonly">

    <span aria-label="copy to clipboard" class="js-zeroclipboard url-box-clippy minibutton zeroclipboard-button" data-clipboard-text="https://github.com/lopatin/sliderTabs.git" data-copied-hint="copied!"><span class="octicon octicon-clippy"></span></span>
  </div>
</div>

  

<div class="clone-url "
  data-protocol-type="subversion"
  data-url="/users/set_protocol?protocol_selector=subversion&amp;protocol_type=clone">
  <h3><strong>Subversion</strong> checkout URL</h3>
  <div class="clone-url-box">
    <input type="text" class="clone js-url-field"
           value="https://github.com/lopatin/sliderTabs" readonly="readonly">

    <span aria-label="copy to clipboard" class="js-zeroclipboard url-box-clippy minibutton zeroclipboard-button" data-clipboard-text="https://github.com/lopatin/sliderTabs" data-copied-hint="copied!"><span class="octicon octicon-clippy"></span></span>
  </div>
</div>


<p class="clone-options">You can clone with
      <a href="#" class="js-clone-selector" data-protocol="http">HTTPS</a>
      or <a href="#" class="js-clone-selector" data-protocol="subversion">Subversion</a>.
  <span class="help tooltipped tooltipped-n" aria-label="Get help on which URL is right for you.">
    <a href="https://help.github.com/articles/which-remote-url-should-i-use">
    <span class="octicon octicon-question"></span>
    </a>
  </span>
</p>



                <a href="/lopatin/sliderTabs/archive/master.zip"
                   class="minibutton sidebar-button"
                   title="Download this repository as a zip file"
                   rel="nofollow">
                  <span class="octicon octicon-cloud-download"></span>
                  Download ZIP
                </a>
              </div>
        </div><!-- /.repository-sidebar -->

        <div id="js-repo-pjax-container" class="repository-content context-loader-container" data-pjax-container>
          


<!-- blob contrib key: blob_contributors:v21:63390264edb3415531882d560769a30f -->

<p title="This is a placeholder element" class="js-history-link-replace hidden"></p>

<a href="/lopatin/sliderTabs/find/master" data-pjax data-hotkey="t" class="js-show-file-finder" style="display:none">Show File Finder</a>

<div class="file-navigation">
  

<div class="select-menu js-menu-container js-select-menu" >
  <span class="minibutton select-menu-button js-menu-target" data-hotkey="w"
    data-master-branch="master"
    data-ref="master"
    role="button" aria-label="Switch branches or tags" tabindex="0" aria-haspopup="true">
    <span class="octicon octicon-git-branch"></span>
    <i>branch:</i>
    <span class="js-select-button">master</span>
  </span>

  <div class="select-menu-modal-holder js-menu-content js-navigation-container" data-pjax aria-hidden="true">

    <div class="select-menu-modal">
      <div class="select-menu-header">
        <span class="select-menu-title">Switch branches/tags</span>
        <span class="octicon octicon-remove-close js-menu-close"></span>
      </div> <!-- /.select-menu-header -->

      <div class="select-menu-filters">
        <div class="select-menu-text-filter">
          <input type="text" aria-label="Filter branches/tags" id="context-commitish-filter-field" class="js-filterable-field js-navigation-enable" placeholder="Filter branches/tags">
        </div>
        <div class="select-menu-tabs">
          <ul>
            <li class="select-menu-tab">
              <a href="#" data-tab-filter="branches" class="js-select-menu-tab">Branches</a>
            </li>
            <li class="select-menu-tab">
              <a href="#" data-tab-filter="tags" class="js-select-menu-tab">Tags</a>
            </li>
          </ul>
        </div><!-- /.select-menu-tabs -->
      </div><!-- /.select-menu-filters -->

      <div class="select-menu-list select-menu-tab-bucket js-select-menu-tab-bucket" data-tab-filter="branches">

        <div data-filterable-for="context-commitish-filter-field" data-filterable-type="substring">


            <div class="select-menu-item js-navigation-item ">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/lopatin/sliderTabs/blob/gh-pages/jquery.sliderTabs.js"
                 data-name="gh-pages"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text js-select-button-text css-truncate-target"
                 title="gh-pages">gh-pages</a>
            </div> <!-- /.select-menu-item -->
            <div class="select-menu-item js-navigation-item selected">
              <span class="select-menu-item-icon octicon octicon-check"></span>
              <a href="/lopatin/sliderTabs/blob/master/jquery.sliderTabs.js"
                 data-name="master"
                 data-skip-pjax="true"
                 rel="nofollow"
                 class="js-navigation-open select-menu-item-text js-select-button-text css-truncate-target"
                 title="master">master</a>
            </div> <!-- /.select-menu-item -->
        </div>

          <div class="select-menu-no-results">Nothing to show</div>
      </div> <!-- /.select-menu-list -->

      <div class="select-menu-list select-menu-tab-bucket js-select-menu-tab-bucket" data-tab-filter="tags">
        <div data-filterable-for="context-commitish-filter-field" data-filterable-type="substring">


        </div>

        <div class="select-menu-no-results">Nothing to show</div>
      </div> <!-- /.select-menu-list -->

    </div> <!-- /.select-menu-modal -->
  </div> <!-- /.select-menu-modal-holder -->
</div> <!-- /.select-menu -->

  <div class="breadcrumb">
    <span class='repo-root js-repo-root'><span itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb"><a href="/lopatin/sliderTabs" data-branch="master" data-direction="back" data-pjax="true" itemscope="url"><span itemprop="title">sliderTabs</span></a></span></span><span class="separator"> / </span><strong class="final-path">jquery.sliderTabs.js</strong> <span aria-label="copy to clipboard" class="js-zeroclipboard minibutton zeroclipboard-button" data-clipboard-text="jquery.sliderTabs.js" data-copied-hint="copied!"><span class="octicon octicon-clippy"></span></span>
  </div>
</div>


  <div class="commit file-history-tease">
    <img alt="Alex Lopatin" class="main-avatar js-avatar" data-user="1187758" height="24" src="https://2.gravatar.com/avatar/b3fd692654b29d15010cf8ea793ef8ca?d=https%3A%2F%2Fidenticons.github.com%2Feed21c184b4302bb89fbb4dbb4da33c0.png&amp;r=x&amp;s=140" width="24" />
    <span class="author"><a href="/lopatin" rel="author">lopatin</a></span>
    <time class="js-relative-date" data-title-format="YYYY-MM-DD HH:mm:ss" datetime="2012-02-26T21:20:22-08:00" title="2012-02-26 21:20:22">February 26, 2012</time>
    <div class="commit-title">
        <a href="/lopatin/sliderTabs/commit/d3bc83c2fb5545a6221c243171374cd8e92f3ca0" class="message" data-pjax="true" title="Bug fixes">Bug fixes</a>
    </div>

    <div class="participation">
      <p class="quickstat"><a href="#blob_contributors_box" rel="facebox"><strong>1</strong> contributor</a></p>
      
    </div>
    <div id="blob_contributors_box" style="display:none">
      <h2 class="facebox-header">Users who have contributed to this file</h2>
      <ul class="facebox-user-list">
          <li class="facebox-user-list-item">
            <img alt="Alex Lopatin" class=" js-avatar" data-user="1187758" height="24" src="https://2.gravatar.com/avatar/b3fd692654b29d15010cf8ea793ef8ca?d=https%3A%2F%2Fidenticons.github.com%2Feed21c184b4302bb89fbb4dbb4da33c0.png&amp;r=x&amp;s=140" width="24" />
            <a href="/lopatin">lopatin</a>
          </li>
      </ul>
    </div>
  </div>

<div class="file-box">
  <div class="file">
    <div class="meta clearfix">
      <div class="info file-name">
        <span class="icon"><b class="octicon octicon-file-text"></b></span>
        <span class="mode" title="File Mode">file</span>
        <span class="meta-divider"></span>
          <span>830 lines (718 sloc)</span>
          <span class="meta-divider"></span>
        <span>25.658 kb</span>
      </div>
      <div class="actions">
        <div class="button-group">
              <a class="minibutton disabled tooltipped tooltipped-w" href="#"
                 aria-label="You must be signed in to make or propose changes">Edit</a>
          <a href="/lopatin/sliderTabs/raw/master/jquery.sliderTabs.js" class="button minibutton " id="raw-url">Raw</a>
            <a href="/lopatin/sliderTabs/blame/master/jquery.sliderTabs.js" class="button minibutton js-update-url-with-hash">Blame</a>
          <a href="/lopatin/sliderTabs/commits/master/jquery.sliderTabs.js" class="button minibutton " rel="nofollow">History</a>
        </div><!-- /.button-group -->
          <a class="minibutton danger disabled empty-icon tooltipped tooltipped-w" href="#"
             aria-label="You must be signed in to make or propose changes">
          Delete
        </a>
      </div><!-- /.actions -->
    </div>
        <div class="blob-wrapper data type-javascript js-blob-data">
        <table class="file-code file-diff tab-size-8">
          <tr class="file-code-line">
            <td class="blob-line-nums">
              <span id="L1" rel="#L1">1</span>
<span id="L2" rel="#L2">2</span>
<span id="L3" rel="#L3">3</span>
<span id="L4" rel="#L4">4</span>
<span id="L5" rel="#L5">5</span>
<span id="L6" rel="#L6">6</span>
<span id="L7" rel="#L7">7</span>
<span id="L8" rel="#L8">8</span>
<span id="L9" rel="#L9">9</span>
<span id="L10" rel="#L10">10</span>
<span id="L11" rel="#L11">11</span>
<span id="L12" rel="#L12">12</span>
<span id="L13" rel="#L13">13</span>
<span id="L14" rel="#L14">14</span>
<span id="L15" rel="#L15">15</span>
<span id="L16" rel="#L16">16</span>
<span id="L17" rel="#L17">17</span>
<span id="L18" rel="#L18">18</span>
<span id="L19" rel="#L19">19</span>
<span id="L20" rel="#L20">20</span>
<span id="L21" rel="#L21">21</span>
<span id="L22" rel="#L22">22</span>
<span id="L23" rel="#L23">23</span>
<span id="L24" rel="#L24">24</span>
<span id="L25" rel="#L25">25</span>
<span id="L26" rel="#L26">26</span>
<span id="L27" rel="#L27">27</span>
<span id="L28" rel="#L28">28</span>
<span id="L29" rel="#L29">29</span>
<span id="L30" rel="#L30">30</span>
<span id="L31" rel="#L31">31</span>
<span id="L32" rel="#L32">32</span>
<span id="L33" rel="#L33">33</span>
<span id="L34" rel="#L34">34</span>
<span id="L35" rel="#L35">35</span>
<span id="L36" rel="#L36">36</span>
<span id="L37" rel="#L37">37</span>
<span id="L38" rel="#L38">38</span>
<span id="L39" rel="#L39">39</span>
<span id="L40" rel="#L40">40</span>
<span id="L41" rel="#L41">41</span>
<span id="L42" rel="#L42">42</span>
<span id="L43" rel="#L43">43</span>
<span id="L44" rel="#L44">44</span>
<span id="L45" rel="#L45">45</span>
<span id="L46" rel="#L46">46</span>
<span id="L47" rel="#L47">47</span>
<span id="L48" rel="#L48">48</span>
<span id="L49" rel="#L49">49</span>
<span id="L50" rel="#L50">50</span>
<span id="L51" rel="#L51">51</span>
<span id="L52" rel="#L52">52</span>
<span id="L53" rel="#L53">53</span>
<span id="L54" rel="#L54">54</span>
<span id="L55" rel="#L55">55</span>
<span id="L56" rel="#L56">56</span>
<span id="L57" rel="#L57">57</span>
<span id="L58" rel="#L58">58</span>
<span id="L59" rel="#L59">59</span>
<span id="L60" rel="#L60">60</span>
<span id="L61" rel="#L61">61</span>
<span id="L62" rel="#L62">62</span>
<span id="L63" rel="#L63">63</span>
<span id="L64" rel="#L64">64</span>
<span id="L65" rel="#L65">65</span>
<span id="L66" rel="#L66">66</span>
<span id="L67" rel="#L67">67</span>
<span id="L68" rel="#L68">68</span>
<span id="L69" rel="#L69">69</span>
<span id="L70" rel="#L70">70</span>
<span id="L71" rel="#L71">71</span>
<span id="L72" rel="#L72">72</span>
<span id="L73" rel="#L73">73</span>
<span id="L74" rel="#L74">74</span>
<span id="L75" rel="#L75">75</span>
<span id="L76" rel="#L76">76</span>
<span id="L77" rel="#L77">77</span>
<span id="L78" rel="#L78">78</span>
<span id="L79" rel="#L79">79</span>
<span id="L80" rel="#L80">80</span>
<span id="L81" rel="#L81">81</span>
<span id="L82" rel="#L82">82</span>
<span id="L83" rel="#L83">83</span>
<span id="L84" rel="#L84">84</span>
<span id="L85" rel="#L85">85</span>
<span id="L86" rel="#L86">86</span>
<span id="L87" rel="#L87">87</span>
<span id="L88" rel="#L88">88</span>
<span id="L89" rel="#L89">89</span>
<span id="L90" rel="#L90">90</span>
<span id="L91" rel="#L91">91</span>
<span id="L92" rel="#L92">92</span>
<span id="L93" rel="#L93">93</span>
<span id="L94" rel="#L94">94</span>
<span id="L95" rel="#L95">95</span>
<span id="L96" rel="#L96">96</span>
<span id="L97" rel="#L97">97</span>
<span id="L98" rel="#L98">98</span>
<span id="L99" rel="#L99">99</span>
<span id="L100" rel="#L100">100</span>
<span id="L101" rel="#L101">101</span>
<span id="L102" rel="#L102">102</span>
<span id="L103" rel="#L103">103</span>
<span id="L104" rel="#L104">104</span>
<span id="L105" rel="#L105">105</span>
<span id="L106" rel="#L106">106</span>
<span id="L107" rel="#L107">107</span>
<span id="L108" rel="#L108">108</span>
<span id="L109" rel="#L109">109</span>
<span id="L110" rel="#L110">110</span>
<span id="L111" rel="#L111">111</span>
<span id="L112" rel="#L112">112</span>
<span id="L113" rel="#L113">113</span>
<span id="L114" rel="#L114">114</span>
<span id="L115" rel="#L115">115</span>
<span id="L116" rel="#L116">116</span>
<span id="L117" rel="#L117">117</span>
<span id="L118" rel="#L118">118</span>
<span id="L119" rel="#L119">119</span>
<span id="L120" rel="#L120">120</span>
<span id="L121" rel="#L121">121</span>
<span id="L122" rel="#L122">122</span>
<span id="L123" rel="#L123">123</span>
<span id="L124" rel="#L124">124</span>
<span id="L125" rel="#L125">125</span>
<span id="L126" rel="#L126">126</span>
<span id="L127" rel="#L127">127</span>
<span id="L128" rel="#L128">128</span>
<span id="L129" rel="#L129">129</span>
<span id="L130" rel="#L130">130</span>
<span id="L131" rel="#L131">131</span>
<span id="L132" rel="#L132">132</span>
<span id="L133" rel="#L133">133</span>
<span id="L134" rel="#L134">134</span>
<span id="L135" rel="#L135">135</span>
<span id="L136" rel="#L136">136</span>
<span id="L137" rel="#L137">137</span>
<span id="L138" rel="#L138">138</span>
<span id="L139" rel="#L139">139</span>
<span id="L140" rel="#L140">140</span>
<span id="L141" rel="#L141">141</span>
<span id="L142" rel="#L142">142</span>
<span id="L143" rel="#L143">143</span>
<span id="L144" rel="#L144">144</span>
<span id="L145" rel="#L145">145</span>
<span id="L146" rel="#L146">146</span>
<span id="L147" rel="#L147">147</span>
<span id="L148" rel="#L148">148</span>
<span id="L149" rel="#L149">149</span>
<span id="L150" rel="#L150">150</span>
<span id="L151" rel="#L151">151</span>
<span id="L152" rel="#L152">152</span>
<span id="L153" rel="#L153">153</span>
<span id="L154" rel="#L154">154</span>
<span id="L155" rel="#L155">155</span>
<span id="L156" rel="#L156">156</span>
<span id="L157" rel="#L157">157</span>
<span id="L158" rel="#L158">158</span>
<span id="L159" rel="#L159">159</span>
<span id="L160" rel="#L160">160</span>
<span id="L161" rel="#L161">161</span>
<span id="L162" rel="#L162">162</span>
<span id="L163" rel="#L163">163</span>
<span id="L164" rel="#L164">164</span>
<span id="L165" rel="#L165">165</span>
<span id="L166" rel="#L166">166</span>
<span id="L167" rel="#L167">167</span>
<span id="L168" rel="#L168">168</span>
<span id="L169" rel="#L169">169</span>
<span id="L170" rel="#L170">170</span>
<span id="L171" rel="#L171">171</span>
<span id="L172" rel="#L172">172</span>
<span id="L173" rel="#L173">173</span>
<span id="L174" rel="#L174">174</span>
<span id="L175" rel="#L175">175</span>
<span id="L176" rel="#L176">176</span>
<span id="L177" rel="#L177">177</span>
<span id="L178" rel="#L178">178</span>
<span id="L179" rel="#L179">179</span>
<span id="L180" rel="#L180">180</span>
<span id="L181" rel="#L181">181</span>
<span id="L182" rel="#L182">182</span>
<span id="L183" rel="#L183">183</span>
<span id="L184" rel="#L184">184</span>
<span id="L185" rel="#L185">185</span>
<span id="L186" rel="#L186">186</span>
<span id="L187" rel="#L187">187</span>
<span id="L188" rel="#L188">188</span>
<span id="L189" rel="#L189">189</span>
<span id="L190" rel="#L190">190</span>
<span id="L191" rel="#L191">191</span>
<span id="L192" rel="#L192">192</span>
<span id="L193" rel="#L193">193</span>
<span id="L194" rel="#L194">194</span>
<span id="L195" rel="#L195">195</span>
<span id="L196" rel="#L196">196</span>
<span id="L197" rel="#L197">197</span>
<span id="L198" rel="#L198">198</span>
<span id="L199" rel="#L199">199</span>
<span id="L200" rel="#L200">200</span>
<span id="L201" rel="#L201">201</span>
<span id="L202" rel="#L202">202</span>
<span id="L203" rel="#L203">203</span>
<span id="L204" rel="#L204">204</span>
<span id="L205" rel="#L205">205</span>
<span id="L206" rel="#L206">206</span>
<span id="L207" rel="#L207">207</span>
<span id="L208" rel="#L208">208</span>
<span id="L209" rel="#L209">209</span>
<span id="L210" rel="#L210">210</span>
<span id="L211" rel="#L211">211</span>
<span id="L212" rel="#L212">212</span>
<span id="L213" rel="#L213">213</span>
<span id="L214" rel="#L214">214</span>
<span id="L215" rel="#L215">215</span>
<span id="L216" rel="#L216">216</span>
<span id="L217" rel="#L217">217</span>
<span id="L218" rel="#L218">218</span>
<span id="L219" rel="#L219">219</span>
<span id="L220" rel="#L220">220</span>
<span id="L221" rel="#L221">221</span>
<span id="L222" rel="#L222">222</span>
<span id="L223" rel="#L223">223</span>
<span id="L224" rel="#L224">224</span>
<span id="L225" rel="#L225">225</span>
<span id="L226" rel="#L226">226</span>
<span id="L227" rel="#L227">227</span>
<span id="L228" rel="#L228">228</span>
<span id="L229" rel="#L229">229</span>
<span id="L230" rel="#L230">230</span>
<span id="L231" rel="#L231">231</span>
<span id="L232" rel="#L232">232</span>
<span id="L233" rel="#L233">233</span>
<span id="L234" rel="#L234">234</span>
<span id="L235" rel="#L235">235</span>
<span id="L236" rel="#L236">236</span>
<span id="L237" rel="#L237">237</span>
<span id="L238" rel="#L238">238</span>
<span id="L239" rel="#L239">239</span>
<span id="L240" rel="#L240">240</span>
<span id="L241" rel="#L241">241</span>
<span id="L242" rel="#L242">242</span>
<span id="L243" rel="#L243">243</span>
<span id="L244" rel="#L244">244</span>
<span id="L245" rel="#L245">245</span>
<span id="L246" rel="#L246">246</span>
<span id="L247" rel="#L247">247</span>
<span id="L248" rel="#L248">248</span>
<span id="L249" rel="#L249">249</span>
<span id="L250" rel="#L250">250</span>
<span id="L251" rel="#L251">251</span>
<span id="L252" rel="#L252">252</span>
<span id="L253" rel="#L253">253</span>
<span id="L254" rel="#L254">254</span>
<span id="L255" rel="#L255">255</span>
<span id="L256" rel="#L256">256</span>
<span id="L257" rel="#L257">257</span>
<span id="L258" rel="#L258">258</span>
<span id="L259" rel="#L259">259</span>
<span id="L260" rel="#L260">260</span>
<span id="L261" rel="#L261">261</span>
<span id="L262" rel="#L262">262</span>
<span id="L263" rel="#L263">263</span>
<span id="L264" rel="#L264">264</span>
<span id="L265" rel="#L265">265</span>
<span id="L266" rel="#L266">266</span>
<span id="L267" rel="#L267">267</span>
<span id="L268" rel="#L268">268</span>
<span id="L269" rel="#L269">269</span>
<span id="L270" rel="#L270">270</span>
<span id="L271" rel="#L271">271</span>
<span id="L272" rel="#L272">272</span>
<span id="L273" rel="#L273">273</span>
<span id="L274" rel="#L274">274</span>
<span id="L275" rel="#L275">275</span>
<span id="L276" rel="#L276">276</span>
<span id="L277" rel="#L277">277</span>
<span id="L278" rel="#L278">278</span>
<span id="L279" rel="#L279">279</span>
<span id="L280" rel="#L280">280</span>
<span id="L281" rel="#L281">281</span>
<span id="L282" rel="#L282">282</span>
<span id="L283" rel="#L283">283</span>
<span id="L284" rel="#L284">284</span>
<span id="L285" rel="#L285">285</span>
<span id="L286" rel="#L286">286</span>
<span id="L287" rel="#L287">287</span>
<span id="L288" rel="#L288">288</span>
<span id="L289" rel="#L289">289</span>
<span id="L290" rel="#L290">290</span>
<span id="L291" rel="#L291">291</span>
<span id="L292" rel="#L292">292</span>
<span id="L293" rel="#L293">293</span>
<span id="L294" rel="#L294">294</span>
<span id="L295" rel="#L295">295</span>
<span id="L296" rel="#L296">296</span>
<span id="L297" rel="#L297">297</span>
<span id="L298" rel="#L298">298</span>
<span id="L299" rel="#L299">299</span>
<span id="L300" rel="#L300">300</span>
<span id="L301" rel="#L301">301</span>
<span id="L302" rel="#L302">302</span>
<span id="L303" rel="#L303">303</span>
<span id="L304" rel="#L304">304</span>
<span id="L305" rel="#L305">305</span>
<span id="L306" rel="#L306">306</span>
<span id="L307" rel="#L307">307</span>
<span id="L308" rel="#L308">308</span>
<span id="L309" rel="#L309">309</span>
<span id="L310" rel="#L310">310</span>
<span id="L311" rel="#L311">311</span>
<span id="L312" rel="#L312">312</span>
<span id="L313" rel="#L313">313</span>
<span id="L314" rel="#L314">314</span>
<span id="L315" rel="#L315">315</span>
<span id="L316" rel="#L316">316</span>
<span id="L317" rel="#L317">317</span>
<span id="L318" rel="#L318">318</span>
<span id="L319" rel="#L319">319</span>
<span id="L320" rel="#L320">320</span>
<span id="L321" rel="#L321">321</span>
<span id="L322" rel="#L322">322</span>
<span id="L323" rel="#L323">323</span>
<span id="L324" rel="#L324">324</span>
<span id="L325" rel="#L325">325</span>
<span id="L326" rel="#L326">326</span>
<span id="L327" rel="#L327">327</span>
<span id="L328" rel="#L328">328</span>
<span id="L329" rel="#L329">329</span>
<span id="L330" rel="#L330">330</span>
<span id="L331" rel="#L331">331</span>
<span id="L332" rel="#L332">332</span>
<span id="L333" rel="#L333">333</span>
<span id="L334" rel="#L334">334</span>
<span id="L335" rel="#L335">335</span>
<span id="L336" rel="#L336">336</span>
<span id="L337" rel="#L337">337</span>
<span id="L338" rel="#L338">338</span>
<span id="L339" rel="#L339">339</span>
<span id="L340" rel="#L340">340</span>
<span id="L341" rel="#L341">341</span>
<span id="L342" rel="#L342">342</span>
<span id="L343" rel="#L343">343</span>
<span id="L344" rel="#L344">344</span>
<span id="L345" rel="#L345">345</span>
<span id="L346" rel="#L346">346</span>
<span id="L347" rel="#L347">347</span>
<span id="L348" rel="#L348">348</span>
<span id="L349" rel="#L349">349</span>
<span id="L350" rel="#L350">350</span>
<span id="L351" rel="#L351">351</span>
<span id="L352" rel="#L352">352</span>
<span id="L353" rel="#L353">353</span>
<span id="L354" rel="#L354">354</span>
<span id="L355" rel="#L355">355</span>
<span id="L356" rel="#L356">356</span>
<span id="L357" rel="#L357">357</span>
<span id="L358" rel="#L358">358</span>
<span id="L359" rel="#L359">359</span>
<span id="L360" rel="#L360">360</span>
<span id="L361" rel="#L361">361</span>
<span id="L362" rel="#L362">362</span>
<span id="L363" rel="#L363">363</span>
<span id="L364" rel="#L364">364</span>
<span id="L365" rel="#L365">365</span>
<span id="L366" rel="#L366">366</span>
<span id="L367" rel="#L367">367</span>
<span id="L368" rel="#L368">368</span>
<span id="L369" rel="#L369">369</span>
<span id="L370" rel="#L370">370</span>
<span id="L371" rel="#L371">371</span>
<span id="L372" rel="#L372">372</span>
<span id="L373" rel="#L373">373</span>
<span id="L374" rel="#L374">374</span>
<span id="L375" rel="#L375">375</span>
<span id="L376" rel="#L376">376</span>
<span id="L377" rel="#L377">377</span>
<span id="L378" rel="#L378">378</span>
<span id="L379" rel="#L379">379</span>
<span id="L380" rel="#L380">380</span>
<span id="L381" rel="#L381">381</span>
<span id="L382" rel="#L382">382</span>
<span id="L383" rel="#L383">383</span>
<span id="L384" rel="#L384">384</span>
<span id="L385" rel="#L385">385</span>
<span id="L386" rel="#L386">386</span>
<span id="L387" rel="#L387">387</span>
<span id="L388" rel="#L388">388</span>
<span id="L389" rel="#L389">389</span>
<span id="L390" rel="#L390">390</span>
<span id="L391" rel="#L391">391</span>
<span id="L392" rel="#L392">392</span>
<span id="L393" rel="#L393">393</span>
<span id="L394" rel="#L394">394</span>
<span id="L395" rel="#L395">395</span>
<span id="L396" rel="#L396">396</span>
<span id="L397" rel="#L397">397</span>
<span id="L398" rel="#L398">398</span>
<span id="L399" rel="#L399">399</span>
<span id="L400" rel="#L400">400</span>
<span id="L401" rel="#L401">401</span>
<span id="L402" rel="#L402">402</span>
<span id="L403" rel="#L403">403</span>
<span id="L404" rel="#L404">404</span>
<span id="L405" rel="#L405">405</span>
<span id="L406" rel="#L406">406</span>
<span id="L407" rel="#L407">407</span>
<span id="L408" rel="#L408">408</span>
<span id="L409" rel="#L409">409</span>
<span id="L410" rel="#L410">410</span>
<span id="L411" rel="#L411">411</span>
<span id="L412" rel="#L412">412</span>
<span id="L413" rel="#L413">413</span>
<span id="L414" rel="#L414">414</span>
<span id="L415" rel="#L415">415</span>
<span id="L416" rel="#L416">416</span>
<span id="L417" rel="#L417">417</span>
<span id="L418" rel="#L418">418</span>
<span id="L419" rel="#L419">419</span>
<span id="L420" rel="#L420">420</span>
<span id="L421" rel="#L421">421</span>
<span id="L422" rel="#L422">422</span>
<span id="L423" rel="#L423">423</span>
<span id="L424" rel="#L424">424</span>
<span id="L425" rel="#L425">425</span>
<span id="L426" rel="#L426">426</span>
<span id="L427" rel="#L427">427</span>
<span id="L428" rel="#L428">428</span>
<span id="L429" rel="#L429">429</span>
<span id="L430" rel="#L430">430</span>
<span id="L431" rel="#L431">431</span>
<span id="L432" rel="#L432">432</span>
<span id="L433" rel="#L433">433</span>
<span id="L434" rel="#L434">434</span>
<span id="L435" rel="#L435">435</span>
<span id="L436" rel="#L436">436</span>
<span id="L437" rel="#L437">437</span>
<span id="L438" rel="#L438">438</span>
<span id="L439" rel="#L439">439</span>
<span id="L440" rel="#L440">440</span>
<span id="L441" rel="#L441">441</span>
<span id="L442" rel="#L442">442</span>
<span id="L443" rel="#L443">443</span>
<span id="L444" rel="#L444">444</span>
<span id="L445" rel="#L445">445</span>
<span id="L446" rel="#L446">446</span>
<span id="L447" rel="#L447">447</span>
<span id="L448" rel="#L448">448</span>
<span id="L449" rel="#L449">449</span>
<span id="L450" rel="#L450">450</span>
<span id="L451" rel="#L451">451</span>
<span id="L452" rel="#L452">452</span>
<span id="L453" rel="#L453">453</span>
<span id="L454" rel="#L454">454</span>
<span id="L455" rel="#L455">455</span>
<span id="L456" rel="#L456">456</span>
<span id="L457" rel="#L457">457</span>
<span id="L458" rel="#L458">458</span>
<span id="L459" rel="#L459">459</span>
<span id="L460" rel="#L460">460</span>
<span id="L461" rel="#L461">461</span>
<span id="L462" rel="#L462">462</span>
<span id="L463" rel="#L463">463</span>
<span id="L464" rel="#L464">464</span>
<span id="L465" rel="#L465">465</span>
<span id="L466" rel="#L466">466</span>
<span id="L467" rel="#L467">467</span>
<span id="L468" rel="#L468">468</span>
<span id="L469" rel="#L469">469</span>
<span id="L470" rel="#L470">470</span>
<span id="L471" rel="#L471">471</span>
<span id="L472" rel="#L472">472</span>
<span id="L473" rel="#L473">473</span>
<span id="L474" rel="#L474">474</span>
<span id="L475" rel="#L475">475</span>
<span id="L476" rel="#L476">476</span>
<span id="L477" rel="#L477">477</span>
<span id="L478" rel="#L478">478</span>
<span id="L479" rel="#L479">479</span>
<span id="L480" rel="#L480">480</span>
<span id="L481" rel="#L481">481</span>
<span id="L482" rel="#L482">482</span>
<span id="L483" rel="#L483">483</span>
<span id="L484" rel="#L484">484</span>
<span id="L485" rel="#L485">485</span>
<span id="L486" rel="#L486">486</span>
<span id="L487" rel="#L487">487</span>
<span id="L488" rel="#L488">488</span>
<span id="L489" rel="#L489">489</span>
<span id="L490" rel="#L490">490</span>
<span id="L491" rel="#L491">491</span>
<span id="L492" rel="#L492">492</span>
<span id="L493" rel="#L493">493</span>
<span id="L494" rel="#L494">494</span>
<span id="L495" rel="#L495">495</span>
<span id="L496" rel="#L496">496</span>
<span id="L497" rel="#L497">497</span>
<span id="L498" rel="#L498">498</span>
<span id="L499" rel="#L499">499</span>
<span id="L500" rel="#L500">500</span>
<span id="L501" rel="#L501">501</span>
<span id="L502" rel="#L502">502</span>
<span id="L503" rel="#L503">503</span>
<span id="L504" rel="#L504">504</span>
<span id="L505" rel="#L505">505</span>
<span id="L506" rel="#L506">506</span>
<span id="L507" rel="#L507">507</span>
<span id="L508" rel="#L508">508</span>
<span id="L509" rel="#L509">509</span>
<span id="L510" rel="#L510">510</span>
<span id="L511" rel="#L511">511</span>
<span id="L512" rel="#L512">512</span>
<span id="L513" rel="#L513">513</span>
<span id="L514" rel="#L514">514</span>
<span id="L515" rel="#L515">515</span>
<span id="L516" rel="#L516">516</span>
<span id="L517" rel="#L517">517</span>
<span id="L518" rel="#L518">518</span>
<span id="L519" rel="#L519">519</span>
<span id="L520" rel="#L520">520</span>
<span id="L521" rel="#L521">521</span>
<span id="L522" rel="#L522">522</span>
<span id="L523" rel="#L523">523</span>
<span id="L524" rel="#L524">524</span>
<span id="L525" rel="#L525">525</span>
<span id="L526" rel="#L526">526</span>
<span id="L527" rel="#L527">527</span>
<span id="L528" rel="#L528">528</span>
<span id="L529" rel="#L529">529</span>
<span id="L530" rel="#L530">530</span>
<span id="L531" rel="#L531">531</span>
<span id="L532" rel="#L532">532</span>
<span id="L533" rel="#L533">533</span>
<span id="L534" rel="#L534">534</span>
<span id="L535" rel="#L535">535</span>
<span id="L536" rel="#L536">536</span>
<span id="L537" rel="#L537">537</span>
<span id="L538" rel="#L538">538</span>
<span id="L539" rel="#L539">539</span>
<span id="L540" rel="#L540">540</span>
<span id="L541" rel="#L541">541</span>
<span id="L542" rel="#L542">542</span>
<span id="L543" rel="#L543">543</span>
<span id="L544" rel="#L544">544</span>
<span id="L545" rel="#L545">545</span>
<span id="L546" rel="#L546">546</span>
<span id="L547" rel="#L547">547</span>
<span id="L548" rel="#L548">548</span>
<span id="L549" rel="#L549">549</span>
<span id="L550" rel="#L550">550</span>
<span id="L551" rel="#L551">551</span>
<span id="L552" rel="#L552">552</span>
<span id="L553" rel="#L553">553</span>
<span id="L554" rel="#L554">554</span>
<span id="L555" rel="#L555">555</span>
<span id="L556" rel="#L556">556</span>
<span id="L557" rel="#L557">557</span>
<span id="L558" rel="#L558">558</span>
<span id="L559" rel="#L559">559</span>
<span id="L560" rel="#L560">560</span>
<span id="L561" rel="#L561">561</span>
<span id="L562" rel="#L562">562</span>
<span id="L563" rel="#L563">563</span>
<span id="L564" rel="#L564">564</span>
<span id="L565" rel="#L565">565</span>
<span id="L566" rel="#L566">566</span>
<span id="L567" rel="#L567">567</span>
<span id="L568" rel="#L568">568</span>
<span id="L569" rel="#L569">569</span>
<span id="L570" rel="#L570">570</span>
<span id="L571" rel="#L571">571</span>
<span id="L572" rel="#L572">572</span>
<span id="L573" rel="#L573">573</span>
<span id="L574" rel="#L574">574</span>
<span id="L575" rel="#L575">575</span>
<span id="L576" rel="#L576">576</span>
<span id="L577" rel="#L577">577</span>
<span id="L578" rel="#L578">578</span>
<span id="L579" rel="#L579">579</span>
<span id="L580" rel="#L580">580</span>
<span id="L581" rel="#L581">581</span>
<span id="L582" rel="#L582">582</span>
<span id="L583" rel="#L583">583</span>
<span id="L584" rel="#L584">584</span>
<span id="L585" rel="#L585">585</span>
<span id="L586" rel="#L586">586</span>
<span id="L587" rel="#L587">587</span>
<span id="L588" rel="#L588">588</span>
<span id="L589" rel="#L589">589</span>
<span id="L590" rel="#L590">590</span>
<span id="L591" rel="#L591">591</span>
<span id="L592" rel="#L592">592</span>
<span id="L593" rel="#L593">593</span>
<span id="L594" rel="#L594">594</span>
<span id="L595" rel="#L595">595</span>
<span id="L596" rel="#L596">596</span>
<span id="L597" rel="#L597">597</span>
<span id="L598" rel="#L598">598</span>
<span id="L599" rel="#L599">599</span>
<span id="L600" rel="#L600">600</span>
<span id="L601" rel="#L601">601</span>
<span id="L602" rel="#L602">602</span>
<span id="L603" rel="#L603">603</span>
<span id="L604" rel="#L604">604</span>
<span id="L605" rel="#L605">605</span>
<span id="L606" rel="#L606">606</span>
<span id="L607" rel="#L607">607</span>
<span id="L608" rel="#L608">608</span>
<span id="L609" rel="#L609">609</span>
<span id="L610" rel="#L610">610</span>
<span id="L611" rel="#L611">611</span>
<span id="L612" rel="#L612">612</span>
<span id="L613" rel="#L613">613</span>
<span id="L614" rel="#L614">614</span>
<span id="L615" rel="#L615">615</span>
<span id="L616" rel="#L616">616</span>
<span id="L617" rel="#L617">617</span>
<span id="L618" rel="#L618">618</span>
<span id="L619" rel="#L619">619</span>
<span id="L620" rel="#L620">620</span>
<span id="L621" rel="#L621">621</span>
<span id="L622" rel="#L622">622</span>
<span id="L623" rel="#L623">623</span>
<span id="L624" rel="#L624">624</span>
<span id="L625" rel="#L625">625</span>
<span id="L626" rel="#L626">626</span>
<span id="L627" rel="#L627">627</span>
<span id="L628" rel="#L628">628</span>
<span id="L629" rel="#L629">629</span>
<span id="L630" rel="#L630">630</span>
<span id="L631" rel="#L631">631</span>
<span id="L632" rel="#L632">632</span>
<span id="L633" rel="#L633">633</span>
<span id="L634" rel="#L634">634</span>
<span id="L635" rel="#L635">635</span>
<span id="L636" rel="#L636">636</span>
<span id="L637" rel="#L637">637</span>
<span id="L638" rel="#L638">638</span>
<span id="L639" rel="#L639">639</span>
<span id="L640" rel="#L640">640</span>
<span id="L641" rel="#L641">641</span>
<span id="L642" rel="#L642">642</span>
<span id="L643" rel="#L643">643</span>
<span id="L644" rel="#L644">644</span>
<span id="L645" rel="#L645">645</span>
<span id="L646" rel="#L646">646</span>
<span id="L647" rel="#L647">647</span>
<span id="L648" rel="#L648">648</span>
<span id="L649" rel="#L649">649</span>
<span id="L650" rel="#L650">650</span>
<span id="L651" rel="#L651">651</span>
<span id="L652" rel="#L652">652</span>
<span id="L653" rel="#L653">653</span>
<span id="L654" rel="#L654">654</span>
<span id="L655" rel="#L655">655</span>
<span id="L656" rel="#L656">656</span>
<span id="L657" rel="#L657">657</span>
<span id="L658" rel="#L658">658</span>
<span id="L659" rel="#L659">659</span>
<span id="L660" rel="#L660">660</span>
<span id="L661" rel="#L661">661</span>
<span id="L662" rel="#L662">662</span>
<span id="L663" rel="#L663">663</span>
<span id="L664" rel="#L664">664</span>
<span id="L665" rel="#L665">665</span>
<span id="L666" rel="#L666">666</span>
<span id="L667" rel="#L667">667</span>
<span id="L668" rel="#L668">668</span>
<span id="L669" rel="#L669">669</span>
<span id="L670" rel="#L670">670</span>
<span id="L671" rel="#L671">671</span>
<span id="L672" rel="#L672">672</span>
<span id="L673" rel="#L673">673</span>
<span id="L674" rel="#L674">674</span>
<span id="L675" rel="#L675">675</span>
<span id="L676" rel="#L676">676</span>
<span id="L677" rel="#L677">677</span>
<span id="L678" rel="#L678">678</span>
<span id="L679" rel="#L679">679</span>
<span id="L680" rel="#L680">680</span>
<span id="L681" rel="#L681">681</span>
<span id="L682" rel="#L682">682</span>
<span id="L683" rel="#L683">683</span>
<span id="L684" rel="#L684">684</span>
<span id="L685" rel="#L685">685</span>
<span id="L686" rel="#L686">686</span>
<span id="L687" rel="#L687">687</span>
<span id="L688" rel="#L688">688</span>
<span id="L689" rel="#L689">689</span>
<span id="L690" rel="#L690">690</span>
<span id="L691" rel="#L691">691</span>
<span id="L692" rel="#L692">692</span>
<span id="L693" rel="#L693">693</span>
<span id="L694" rel="#L694">694</span>
<span id="L695" rel="#L695">695</span>
<span id="L696" rel="#L696">696</span>
<span id="L697" rel="#L697">697</span>
<span id="L698" rel="#L698">698</span>
<span id="L699" rel="#L699">699</span>
<span id="L700" rel="#L700">700</span>
<span id="L701" rel="#L701">701</span>
<span id="L702" rel="#L702">702</span>
<span id="L703" rel="#L703">703</span>
<span id="L704" rel="#L704">704</span>
<span id="L705" rel="#L705">705</span>
<span id="L706" rel="#L706">706</span>
<span id="L707" rel="#L707">707</span>
<span id="L708" rel="#L708">708</span>
<span id="L709" rel="#L709">709</span>
<span id="L710" rel="#L710">710</span>
<span id="L711" rel="#L711">711</span>
<span id="L712" rel="#L712">712</span>
<span id="L713" rel="#L713">713</span>
<span id="L714" rel="#L714">714</span>
<span id="L715" rel="#L715">715</span>
<span id="L716" rel="#L716">716</span>
<span id="L717" rel="#L717">717</span>
<span id="L718" rel="#L718">718</span>
<span id="L719" rel="#L719">719</span>
<span id="L720" rel="#L720">720</span>
<span id="L721" rel="#L721">721</span>
<span id="L722" rel="#L722">722</span>
<span id="L723" rel="#L723">723</span>
<span id="L724" rel="#L724">724</span>
<span id="L725" rel="#L725">725</span>
<span id="L726" rel="#L726">726</span>
<span id="L727" rel="#L727">727</span>
<span id="L728" rel="#L728">728</span>
<span id="L729" rel="#L729">729</span>
<span id="L730" rel="#L730">730</span>
<span id="L731" rel="#L731">731</span>
<span id="L732" rel="#L732">732</span>
<span id="L733" rel="#L733">733</span>
<span id="L734" rel="#L734">734</span>
<span id="L735" rel="#L735">735</span>
<span id="L736" rel="#L736">736</span>
<span id="L737" rel="#L737">737</span>
<span id="L738" rel="#L738">738</span>
<span id="L739" rel="#L739">739</span>
<span id="L740" rel="#L740">740</span>
<span id="L741" rel="#L741">741</span>
<span id="L742" rel="#L742">742</span>
<span id="L743" rel="#L743">743</span>
<span id="L744" rel="#L744">744</span>
<span id="L745" rel="#L745">745</span>
<span id="L746" rel="#L746">746</span>
<span id="L747" rel="#L747">747</span>
<span id="L748" rel="#L748">748</span>
<span id="L749" rel="#L749">749</span>
<span id="L750" rel="#L750">750</span>
<span id="L751" rel="#L751">751</span>
<span id="L752" rel="#L752">752</span>
<span id="L753" rel="#L753">753</span>
<span id="L754" rel="#L754">754</span>
<span id="L755" rel="#L755">755</span>
<span id="L756" rel="#L756">756</span>
<span id="L757" rel="#L757">757</span>
<span id="L758" rel="#L758">758</span>
<span id="L759" rel="#L759">759</span>
<span id="L760" rel="#L760">760</span>
<span id="L761" rel="#L761">761</span>
<span id="L762" rel="#L762">762</span>
<span id="L763" rel="#L763">763</span>
<span id="L764" rel="#L764">764</span>
<span id="L765" rel="#L765">765</span>
<span id="L766" rel="#L766">766</span>
<span id="L767" rel="#L767">767</span>
<span id="L768" rel="#L768">768</span>
<span id="L769" rel="#L769">769</span>
<span id="L770" rel="#L770">770</span>
<span id="L771" rel="#L771">771</span>
<span id="L772" rel="#L772">772</span>
<span id="L773" rel="#L773">773</span>
<span id="L774" rel="#L774">774</span>
<span id="L775" rel="#L775">775</span>
<span id="L776" rel="#L776">776</span>
<span id="L777" rel="#L777">777</span>
<span id="L778" rel="#L778">778</span>
<span id="L779" rel="#L779">779</span>
<span id="L780" rel="#L780">780</span>
<span id="L781" rel="#L781">781</span>
<span id="L782" rel="#L782">782</span>
<span id="L783" rel="#L783">783</span>
<span id="L784" rel="#L784">784</span>
<span id="L785" rel="#L785">785</span>
<span id="L786" rel="#L786">786</span>
<span id="L787" rel="#L787">787</span>
<span id="L788" rel="#L788">788</span>
<span id="L789" rel="#L789">789</span>
<span id="L790" rel="#L790">790</span>
<span id="L791" rel="#L791">791</span>
<span id="L792" rel="#L792">792</span>
<span id="L793" rel="#L793">793</span>
<span id="L794" rel="#L794">794</span>
<span id="L795" rel="#L795">795</span>
<span id="L796" rel="#L796">796</span>
<span id="L797" rel="#L797">797</span>
<span id="L798" rel="#L798">798</span>
<span id="L799" rel="#L799">799</span>
<span id="L800" rel="#L800">800</span>
<span id="L801" rel="#L801">801</span>
<span id="L802" rel="#L802">802</span>
<span id="L803" rel="#L803">803</span>
<span id="L804" rel="#L804">804</span>
<span id="L805" rel="#L805">805</span>
<span id="L806" rel="#L806">806</span>
<span id="L807" rel="#L807">807</span>
<span id="L808" rel="#L808">808</span>
<span id="L809" rel="#L809">809</span>
<span id="L810" rel="#L810">810</span>
<span id="L811" rel="#L811">811</span>
<span id="L812" rel="#L812">812</span>
<span id="L813" rel="#L813">813</span>
<span id="L814" rel="#L814">814</span>
<span id="L815" rel="#L815">815</span>
<span id="L816" rel="#L816">816</span>
<span id="L817" rel="#L817">817</span>
<span id="L818" rel="#L818">818</span>
<span id="L819" rel="#L819">819</span>
<span id="L820" rel="#L820">820</span>
<span id="L821" rel="#L821">821</span>
<span id="L822" rel="#L822">822</span>
<span id="L823" rel="#L823">823</span>
<span id="L824" rel="#L824">824</span>
<span id="L825" rel="#L825">825</span>
<span id="L826" rel="#L826">826</span>
<span id="L827" rel="#L827">827</span>
<span id="L828" rel="#L828">828</span>
<span id="L829" rel="#L829">829</span>
<span id="L830" rel="#L830">830</span>

            </td>
            <td class="blob-line-code"><div class="code-body highlight"><pre><div class='line' id='LC1'><span class="cm">/*</span></div><div class='line' id='LC2'><span class="cm"> * jQuery SliderTabs v1.1</span></div><div class='line' id='LC3'><span class="cm"> * http://lopatin.github.com/sliderTabs</span></div><div class='line' id='LC4'><span class="cm"> *</span></div><div class='line' id='LC5'><span class="cm"> * Copyright 2012, Alex Lopatin</span></div><div class='line' id='LC6'><span class="cm"> * Free to use under the MIT license.</span></div><div class='line' id='LC7'><span class="cm"> * http://www.opensource.org/licenses/mit-license.php</span></div><div class='line' id='LC8'><span class="cm"> *</span></div><div class='line' id='LC9'><span class="cm"> */</span></div><div class='line' id='LC10'><br/></div><div class='line' id='LC11'><br/></div><div class='line' id='LC12'>&nbsp;<span class="p">(</span><span class="kd">function</span><span class="p">(</span> <span class="nx">$</span> <span class="p">){</span></div><div class='line' id='LC13'>	<span class="cm">/*</span></div><div class='line' id='LC14'><span class="cm">	 * The sliderTabs tabs class</span></div><div class='line' id='LC15'><span class="cm">	 */</span></div><div class='line' id='LC16'>	<span class="nx">$</span><span class="p">.</span><span class="nx">sliderTabs</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(</span><span class="nx">container</span><span class="p">,</span> <span class="nx">options</span><span class="p">){</span></div><div class='line' id='LC17'>		<span class="kd">var</span> <span class="nx">plugin</span> <span class="o">=</span> <span class="k">this</span><span class="p">;</span></div><div class='line' id='LC18'><br/></div><div class='line' id='LC19'>		<span class="kd">var</span> <span class="nx">defaults</span> <span class="o">=</span> <span class="p">{</span></div><div class='line' id='LC20'>			<span class="nx">autoplay</span><span class="o">:</span> <span class="kc">false</span><span class="p">,</span></div><div class='line' id='LC21'>			<span class="nx">tabArrowWidth</span><span class="o">:</span> <span class="mi">35</span><span class="p">,</span></div><div class='line' id='LC22'>			<span class="nx">classes</span><span class="o">:</span> <span class="p">{</span></div><div class='line' id='LC23'>				<span class="nx">leftTabArrow</span><span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">,</span></div><div class='line' id='LC24'>				<span class="nx">panel</span><span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">,</span></div><div class='line' id='LC25'>				<span class="nx">panelActive</span><span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">,</span></div><div class='line' id='LC26'>				<span class="nx">panelsContainer</span><span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">,</span></div><div class='line' id='LC27'>				<span class="nx">rightTabArrow</span><span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">,</span></div><div class='line' id='LC28'>				<span class="nx">tab</span><span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">,</span></div><div class='line' id='LC29'>				<span class="nx">tabActive</span><span class="o">:</span> <span class="s1">&#39;&#39;</span><span class="p">,</span>	</div><div class='line' id='LC30'>				<span class="nx">tabsList</span><span class="o">:</span> <span class="s1">&#39;&#39;</span></div><div class='line' id='LC31'>			<span class="p">},</span></div><div class='line' id='LC32'>			<span class="nx">defaultTab</span><span class="o">:</span> <span class="mi">1</span><span class="p">,</span></div><div class='line' id='LC33'>			<span class="nx">height</span><span class="o">:</span> <span class="kc">null</span><span class="p">,</span></div><div class='line' id='LC34'>			<span class="nx">indicators</span><span class="o">:</span> <span class="kc">false</span><span class="p">,</span></div><div class='line' id='LC35'>			<span class="nx">mousewheel</span><span class="o">:</span> <span class="kc">true</span><span class="p">,</span></div><div class='line' id='LC36'>			<span class="nx">position</span><span class="o">:</span> <span class="s2">&quot;top&quot;</span><span class="p">,</span></div><div class='line' id='LC37'>			<span class="nx">panelArrows</span><span class="o">:</span> <span class="kc">false</span><span class="p">,</span></div><div class='line' id='LC38'>			<span class="nx">panelArrowsShowOnHover</span><span class="o">:</span> <span class="kc">false</span><span class="p">,</span></div><div class='line' id='LC39'>			<span class="nx">tabs</span><span class="o">:</span> <span class="kc">true</span><span class="p">,</span></div><div class='line' id='LC40'>			<span class="nx">tabHeight</span><span class="o">:</span> <span class="mi">30</span><span class="p">,</span></div><div class='line' id='LC41'>			<span class="nx">tabArrows</span><span class="o">:</span> <span class="kc">true</span><span class="p">,</span></div><div class='line' id='LC42'>			<span class="nx">tabSlideLength</span><span class="o">:</span> <span class="mi">100</span><span class="p">,</span></div><div class='line' id='LC43'>			<span class="nx">tabSlideSpeed</span><span class="o">:</span> <span class="mi">200</span><span class="p">,</span></div><div class='line' id='LC44'>			<span class="nx">transition</span><span class="o">:</span> <span class="s1">&#39;slide&#39;</span><span class="p">,</span></div><div class='line' id='LC45'>			<span class="nx">transitionEasing</span><span class="o">:</span> <span class="s1">&#39;easeOutCubic&#39;</span><span class="p">,</span></div><div class='line' id='LC46'>			<span class="nx">transitionSpeed</span><span class="o">:</span> <span class="mi">500</span><span class="p">,</span></div><div class='line' id='LC47'>			<span class="nx">width</span><span class="o">:</span> <span class="kc">null</span></div><div class='line' id='LC48'>		<span class="p">};</span></div><div class='line' id='LC49'><br/></div><div class='line' id='LC50'>		<span class="c1">// jQuery objects of important elements</span></div><div class='line' id='LC51'>		<span class="kd">var</span> <span class="nx">$container</span> <span class="o">=</span> <span class="nx">$</span><span class="p">(</span><span class="nx">container</span><span class="p">),</span></div><div class='line' id='LC52'>			<span class="nx">$indicators</span><span class="p">,</span></div><div class='line' id='LC53'>			<span class="nx">$tabsList</span><span class="p">,</span></div><div class='line' id='LC54'>			<span class="nx">$contentDivs</span><span class="p">,</span></div><div class='line' id='LC55'>			<span class="nx">$tabsListContainer</span><span class="p">,</span></div><div class='line' id='LC56'>			<span class="nx">$tabsListWrapper</span><span class="p">,</span></div><div class='line' id='LC57'>			<span class="nx">$contentDivsContainer</span><span class="p">,</span></div><div class='line' id='LC58'>			<span class="nx">$leftTabArrow</span><span class="p">,</span></div><div class='line' id='LC59'>			<span class="nx">$rightTabArrow</span><span class="p">,</span></div><div class='line' id='LC60'>			<span class="nx">$leftPanelArrow</span><span class="p">,</span></div><div class='line' id='LC61'>			<span class="nx">$rightPanelArrow</span><span class="p">;</span></div><div class='line' id='LC62'><br/></div><div class='line' id='LC63'>		<span class="c1">// Locks to stop out of sync behavior</span></div><div class='line' id='LC64'>		<span class="kd">var</span> <span class="nx">selectLock</span> <span class="o">=</span> <span class="kc">false</span><span class="p">,</span></div><div class='line' id='LC65'>			<span class="nx">heightLock</span> <span class="o">=</span> <span class="kc">true</span><span class="p">;</span></div><div class='line' id='LC66'><br/></div><div class='line' id='LC67'>		<span class="kd">var</span> <span class="nx">settings</span><span class="p">,</span> <span class="nx">minMargin</span><span class="p">;</span></div><div class='line' id='LC68'><br/></div><div class='line' id='LC69'>		<span class="c1">// Index of currently selected tab</span></div><div class='line' id='LC70'>		<span class="nx">plugin</span><span class="p">.</span><span class="nx">selectedTab</span> <span class="o">=</span> <span class="nx">defaults</span><span class="p">.</span><span class="nx">defaultTab</span><span class="p">;</span></div><div class='line' id='LC71'><br/></div><div class='line' id='LC72'>		<span class="nx">plugin</span><span class="p">.</span><span class="nx">init</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC73'>			<span class="nx">settings</span> <span class="o">=</span> <span class="nx">plugin</span><span class="p">.</span><span class="nx">settings</span> <span class="o">=</span> <span class="nx">$</span><span class="p">.</span><span class="nx">extend</span><span class="p">({},</span> <span class="nx">defaults</span><span class="p">,</span> <span class="nx">options</span><span class="p">);</span></div><div class='line' id='LC74'>			<span class="nx">$container</span><span class="p">.</span><span class="nx">addClass</span><span class="p">(</span><span class="s1">&#39;ui-slider-tabs&#39;</span><span class="p">);</span></div><div class='line' id='LC75'><br/></div><div class='line' id='LC76'>			<span class="cm">/* </span></div><div class='line' id='LC77'><span class="cm">			 * Rebuild structure of container</span></div><div class='line' id='LC78'><span class="cm">			 */</span></div><div class='line' id='LC79'>			<span class="nx">$contentDivs</span> <span class="o">=</span> <span class="nx">$container</span><span class="p">.</span><span class="nx">children</span><span class="p">(</span><span class="s2">&quot;div&quot;</span><span class="p">).</span><span class="nx">addClass</span><span class="p">(</span><span class="s1">&#39;ui-slider-tab-content&#39;</span><span class="p">).</span><span class="nx">remove</span><span class="p">();</span></div><div class='line' id='LC80'><br/></div><div class='line' id='LC81'>			<span class="c1">// Tabs</span></div><div class='line' id='LC82'>			<span class="nx">$tabsList</span> <span class="o">=</span> <span class="nx">$container</span><span class="p">.</span><span class="nx">children</span><span class="p">(</span><span class="s2">&quot;ul&quot;</span><span class="p">).</span><span class="nx">addClass</span><span class="p">(</span><span class="s1">&#39;ui-slider-tabs-list&#39;</span><span class="p">).</span><span class="nx">remove</span><span class="p">();</span></div><div class='line' id='LC83'>			<span class="nx">$tabsList</span><span class="p">.</span><span class="nx">children</span><span class="p">(</span><span class="s2">&quot;li&quot;</span><span class="p">).</span><span class="nx">remove</span><span class="p">().</span><span class="nx">appendTo</span><span class="p">(</span><span class="nx">$tabsList</span><span class="p">);</span></div><div class='line' id='LC84'>			<span class="nx">plugin</span><span class="p">.</span><span class="nx">count</span> <span class="o">=</span> <span class="nx">$tabsList</span><span class="p">.</span><span class="nx">children</span><span class="p">(</span><span class="s1">&#39;li&#39;</span><span class="p">).</span><span class="nx">length</span><span class="p">;</span></div><div class='line' id='LC85'>			<span class="nx">$tabsListWrapper</span> <span class="o">=</span> <span class="nx">$</span><span class="p">(</span><span class="s2">&quot;&lt;div class=&#39;ui-slider-tabs-list-wrapper&#39;&gt;&quot;</span><span class="p">);</span></div><div class='line' id='LC86'>			<span class="nx">$tabsListContainer</span> <span class="o">=</span> <span class="nx">$</span><span class="p">(</span><span class="s2">&quot;&lt;div class=&#39;ui-slider-tabs-list-container&#39;&gt;&quot;</span><span class="p">).</span><span class="nx">append</span><span class="p">(</span><span class="nx">$tabsList</span><span class="p">).</span><span class="nx">appendTo</span><span class="p">(</span><span class="nx">$tabsListWrapper</span><span class="p">);</span></div><div class='line' id='LC87'>			<span class="nx">$tabsListContainer</span><span class="p">.</span><span class="nx">find</span><span class="p">(</span><span class="s1">&#39;li&#39;</span><span class="p">).</span><span class="nx">css</span><span class="p">(</span><span class="s1">&#39;height&#39;</span><span class="p">,</span> <span class="nx">settings</span><span class="p">.</span><span class="nx">tabHeight</span><span class="o">+</span><span class="mi">2</span><span class="p">);</span></div><div class='line' id='LC88'>			<span class="nx">$tabsListContainer</span><span class="p">.</span><span class="nx">find</span><span class="p">(</span><span class="s1">&#39;li a&#39;</span><span class="p">).</span><span class="nx">css</span><span class="p">(</span><span class="s1">&#39;height&#39;</span><span class="p">,</span> <span class="nx">settings</span><span class="p">.</span><span class="nx">tabHeight</span><span class="o">+</span><span class="mi">2</span><span class="p">);</span></div><div class='line' id='LC89'><br/></div><div class='line' id='LC90'>			<span class="c1">// Tab arrows</span></div><div class='line' id='LC91'>			<span class="nx">$leftTabArrow</span> <span class="o">=</span> <span class="nx">$</span><span class="p">(</span><span class="s2">&quot;&lt;a href=&#39;#&#39; class=&#39;ui-slider-left-arrow&#39;&gt;&lt;div&gt;&lt;/div&gt;&lt;/a&gt;&quot;</span><span class="p">).</span><span class="nx">css</span><span class="p">({</span></div><div class='line' id='LC92'>				<span class="s1">&#39;width&#39;</span><span class="o">:</span> <span class="nx">settings</span><span class="p">.</span><span class="nx">tabArrowWidth</span><span class="p">,</span></div><div class='line' id='LC93'>				<span class="s1">&#39;height&#39;</span><span class="o">:</span> <span class="nx">settings</span><span class="p">.</span><span class="nx">tabHeight</span><span class="o">+</span><span class="mi">2</span></div><div class='line' id='LC94'>			<span class="p">}).</span><span class="nx">appendTo</span><span class="p">(</span><span class="nx">$tabsListContainer</span><span class="p">).</span><span class="nx">click</span><span class="p">(</span><span class="kd">function</span><span class="p">(</span><span class="nx">e</span><span class="p">){</span></div><div class='line' id='LC95'>				<span class="nx">plugin</span><span class="p">.</span><span class="nx">slideTabs</span><span class="p">(</span><span class="s1">&#39;right&#39;</span><span class="p">,</span> <span class="nx">settings</span><span class="p">.</span><span class="nx">tabSlideLength</span><span class="p">);</span></div><div class='line' id='LC96'>				<span class="k">return</span> <span class="kc">false</span><span class="p">;</span></div><div class='line' id='LC97'>			<span class="p">});</span></div><div class='line' id='LC98'>			<span class="nx">$rightTabArrow</span> <span class="o">=</span> <span class="nx">$</span><span class="p">(</span><span class="s2">&quot;&lt;a href=&#39;#&#39; class=&#39;ui-slider-right-arrow&#39;&gt;&lt;div&gt;&lt;/div&gt;&lt;/a&gt;&quot;</span><span class="p">).</span><span class="nx">css</span><span class="p">({</span></div><div class='line' id='LC99'>				<span class="s1">&#39;width&#39;</span><span class="o">:</span> <span class="nx">settings</span><span class="p">.</span><span class="nx">tabArrowWidth</span><span class="p">,</span></div><div class='line' id='LC100'>				<span class="s1">&#39;height&#39;</span><span class="o">:</span> <span class="nx">settings</span><span class="p">.</span><span class="nx">tabHeight</span><span class="o">+</span><span class="mi">2</span></div><div class='line' id='LC101'>			<span class="p">}).</span><span class="nx">appendTo</span><span class="p">(</span><span class="nx">$tabsListContainer</span><span class="p">).</span><span class="nx">click</span><span class="p">(</span><span class="kd">function</span><span class="p">(</span><span class="nx">e</span><span class="p">){</span></div><div class='line' id='LC102'>				<span class="nx">plugin</span><span class="p">.</span><span class="nx">slideTabs</span><span class="p">(</span><span class="s1">&#39;left&#39;</span><span class="p">,</span> <span class="nx">settings</span><span class="p">.</span><span class="nx">tabSlideLength</span><span class="p">);</span></div><div class='line' id='LC103'>				<span class="k">return</span> <span class="kc">false</span><span class="p">;</span></div><div class='line' id='LC104'>			<span class="p">});</span></div><div class='line' id='LC105'><br/></div><div class='line' id='LC106'>			<span class="c1">// Content container</span></div><div class='line' id='LC107'>			<span class="nx">$contentDivsContainer</span> <span class="o">=</span> <span class="nx">$</span><span class="p">(</span><span class="s2">&quot;&lt;div class=&#39;ui-slider-tabs-content-container&#39;&gt;&quot;</span><span class="p">).</span><span class="nx">append</span><span class="p">(</span><span class="nx">$contentDivs</span><span class="p">);</span></div><div class='line' id='LC108'><br/></div><div class='line' id='LC109'>			<span class="c1">// Position the tabs on top or bottom</span></div><div class='line' id='LC110'>			<span class="k">if</span><span class="p">(</span><span class="nx">settings</span><span class="p">.</span><span class="nx">position</span> <span class="o">==</span> <span class="s1">&#39;bottom&#39;</span><span class="p">)</span></div><div class='line' id='LC111'>				<span class="nx">$container</span><span class="p">.</span><span class="nx">append</span><span class="p">(</span><span class="nx">$contentDivsContainer</span><span class="p">).</span><span class="nx">append</span><span class="p">(</span><span class="nx">$tabsListWrapper</span><span class="p">.</span><span class="nx">addClass</span><span class="p">(</span><span class="s1">&#39;bottom&#39;</span><span class="p">));</span></div><div class='line' id='LC112'>			<span class="k">else</span></div><div class='line' id='LC113'>				<span class="nx">$container</span><span class="p">.</span><span class="nx">append</span><span class="p">(</span><span class="nx">$tabsListWrapper</span><span class="p">).</span><span class="nx">append</span><span class="p">(</span><span class="nx">$contentDivsContainer</span><span class="p">);</span></div><div class='line' id='LC114'><br/></div><div class='line' id='LC115'><br/></div><div class='line' id='LC116'>			<span class="k">if</span><span class="p">(</span><span class="nx">settings</span><span class="p">.</span><span class="nx">width</span><span class="p">)</span></div><div class='line' id='LC117'>				<span class="nx">$container</span><span class="p">.</span><span class="nx">width</span><span class="p">(</span><span class="nb">parseInt</span><span class="p">(</span><span class="nx">settings</span><span class="p">.</span><span class="nx">width</span><span class="p">));</span></div><div class='line' id='LC118'>			<span class="k">if</span><span class="p">(</span><span class="nx">settings</span><span class="p">.</span><span class="nx">height</span><span class="p">)</span></div><div class='line' id='LC119'>				<span class="nx">$contentDivsContainer</span><span class="p">.</span><span class="nx">height</span><span class="p">(</span><span class="nb">parseInt</span><span class="p">(</span><span class="nx">settings</span><span class="p">.</span><span class="nx">height</span><span class="p">)</span><span class="o">-</span> <span class="nx">settings</span><span class="p">.</span><span class="nx">tabHeight</span><span class="p">);</span></div><div class='line' id='LC120'><br/></div><div class='line' id='LC121'>			<span class="c1">// Create and show indicators</span></div><div class='line' id='LC122'>			<span class="k">if</span><span class="p">(</span><span class="nx">settings</span><span class="p">.</span><span class="nx">indicators</span><span class="p">)</span></div><div class='line' id='LC123'>				<span class="nx">plugin</span><span class="p">.</span><span class="nx">showIndicators</span><span class="p">();</span></div><div class='line' id='LC124'><br/></div><div class='line' id='LC125'><br/></div><div class='line' id='LC126'>			<span class="c1">// Select default tab</span></div><div class='line' id='LC127'>			<span class="nx">plugin</span><span class="p">.</span><span class="nx">selectTab</span><span class="p">(</span><span class="nx">settings</span><span class="p">.</span><span class="nx">defaultTab</span><span class="p">);</span></div><div class='line' id='LC128'>			<span class="nx">plugin</span><span class="p">.</span><span class="nx">slideTabs</span><span class="p">(</span><span class="s1">&#39;left&#39;</span><span class="p">,</span> <span class="mi">0</span><span class="p">);</span></div><div class='line' id='LC129'><br/></div><div class='line' id='LC130'>			<span class="nx">reorderPanels</span><span class="p">();</span></div><div class='line' id='LC131'><br/></div><div class='line' id='LC132'>			<span class="nx">resizePanels</span><span class="p">();</span></div><div class='line' id='LC133'><br/></div><div class='line' id='LC134'>			<span class="c1">// When tab is clicked</span></div><div class='line' id='LC135'>			<span class="nx">$container</span><span class="p">.</span><span class="nx">delegate</span><span class="p">(</span><span class="s1">&#39;.ui-slider-tabs-list li a&#39;</span><span class="p">,</span> <span class="s1">&#39;click&#39;</span><span class="p">,</span> <span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC136'>				<span class="k">if</span><span class="p">(</span><span class="o">!</span><span class="nx">$</span><span class="p">(</span><span class="k">this</span><span class="p">).</span><span class="nx">parent</span><span class="p">().</span><span class="nx">hasClass</span><span class="p">(</span><span class="s1">&#39;selected&#39;</span><span class="p">)</span> <span class="o">&amp;&amp;</span> <span class="o">!</span><span class="nx">selectLock</span><span class="p">){</span></div><div class='line' id='LC137'>					<span class="nx">plugin</span><span class="p">.</span><span class="nx">selectTab</span><span class="p">(</span><span class="nx">$</span><span class="p">(</span><span class="k">this</span><span class="p">).</span><span class="nx">parent</span><span class="p">());</span></div><div class='line' id='LC138'>				<span class="p">}</span></div><div class='line' id='LC139'>				<span class="k">return</span> <span class="kc">false</span><span class="p">;</span></div><div class='line' id='LC140'>			<span class="p">});</span></div><div class='line' id='LC141'><br/></div><div class='line' id='LC142'>			<span class="c1">// When indicator is clicked</span></div><div class='line' id='LC143'>			<span class="k">if</span><span class="p">(</span><span class="nx">$indicators</span><span class="p">)</span></div><div class='line' id='LC144'>				<span class="nx">$indicators</span><span class="p">.</span><span class="nx">delegate</span><span class="p">(</span><span class="s1">&#39;.ui-slider-tabs-indicator&#39;</span><span class="p">,</span> <span class="s1">&#39;click&#39;</span><span class="p">,</span> <span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC145'>					<span class="k">if</span><span class="p">(</span><span class="o">!</span><span class="nx">$</span><span class="p">(</span><span class="k">this</span><span class="p">).</span><span class="nx">hasClass</span><span class="p">(</span><span class="s1">&#39;selected&#39;</span><span class="p">)</span> <span class="o">&amp;&amp;</span> <span class="o">!</span><span class="nx">selectLock</span><span class="p">)</span></div><div class='line' id='LC146'>						<span class="nx">plugin</span><span class="p">.</span><span class="nx">selectTab</span><span class="p">(</span><span class="nx">$</span><span class="p">(</span><span class="k">this</span><span class="p">).</span><span class="nx">index</span><span class="p">()</span><span class="o">+</span><span class="mi">1</span><span class="p">);</span></div><div class='line' id='LC147'>				<span class="p">});</span></div><div class='line' id='LC148'><br/></div><div class='line' id='LC149'>			<span class="c1">// Set classes</span></div><div class='line' id='LC150'>			<span class="nx">$</span><span class="p">.</span><span class="nx">each</span><span class="p">(</span><span class="nx">settings</span><span class="p">.</span><span class="nx">classes</span><span class="p">,</span> <span class="kd">function</span><span class="p">(</span><span class="nx">i</span><span class="p">,</span> <span class="nx">c</span><span class="p">){</span></div><div class='line' id='LC151'>				<span class="k">switch</span><span class="p">(</span><span class="nx">i</span><span class="p">){</span></div><div class='line' id='LC152'>					<span class="k">case</span> <span class="s1">&#39;leftTabArrow&#39;</span><span class="o">:</span> </div><div class='line' id='LC153'>						<span class="nx">$leftTabArrow</span><span class="p">.</span><span class="nx">addClass</span><span class="p">(</span><span class="nx">c</span><span class="p">);</span></div><div class='line' id='LC154'>						<span class="k">break</span><span class="p">;</span></div><div class='line' id='LC155'>					<span class="k">case</span> <span class="s1">&#39;rightTabArrow&#39;</span><span class="o">:</span></div><div class='line' id='LC156'>						<span class="nx">$rightTabArrow</span><span class="p">.</span><span class="nx">addClass</span><span class="p">(</span><span class="nx">c</span><span class="p">);</span></div><div class='line' id='LC157'>						<span class="k">break</span><span class="p">;</span></div><div class='line' id='LC158'>					<span class="k">case</span> <span class="s1">&#39;panel&#39;</span><span class="o">:</span></div><div class='line' id='LC159'>						<span class="nx">$contentDivs</span><span class="p">.</span><span class="nx">addClass</span><span class="p">(</span><span class="nx">c</span><span class="p">);</span></div><div class='line' id='LC160'>						<span class="k">break</span><span class="p">;</span></div><div class='line' id='LC161'>					<span class="k">case</span> <span class="s1">&#39;panelsContainer&#39;</span><span class="o">:</span></div><div class='line' id='LC162'>						<span class="nx">$contentDivsContainer</span><span class="p">.</span><span class="nx">addClass</span><span class="p">(</span><span class="nx">c</span><span class="p">);</span></div><div class='line' id='LC163'>						<span class="k">break</span><span class="p">;</span></div><div class='line' id='LC164'>					<span class="k">case</span> <span class="s1">&#39;tab&#39;</span><span class="o">:</span></div><div class='line' id='LC165'>						<span class="nx">$tabsList</span><span class="p">.</span><span class="nx">find</span><span class="p">(</span><span class="s1">&#39;li&#39;</span><span class="p">).</span><span class="nx">addClass</span><span class="p">(</span><span class="nx">c</span><span class="p">);</span></div><div class='line' id='LC166'>						<span class="k">break</span><span class="p">;</span></div><div class='line' id='LC167'>					<span class="k">case</span> <span class="s1">&#39;tabsList&#39;</span><span class="o">:</span></div><div class='line' id='LC168'>						<span class="nx">$tabsList</span><span class="p">.</span><span class="nx">addClass</span><span class="p">(</span><span class="nx">c</span><span class="p">);</span></div><div class='line' id='LC169'>						<span class="k">break</span><span class="p">;</span></div><div class='line' id='LC170'>					<span class="k">default</span><span class="o">:</span></div><div class='line' id='LC171'>						<span class="k">break</span><span class="p">;</span></div><div class='line' id='LC172'>				<span class="p">}</span></div><div class='line' id='LC173'>			<span class="p">});</span></div><div class='line' id='LC174'><br/></div><div class='line' id='LC175'>			<span class="c1">// Panel arrows</span></div><div class='line' id='LC176'>			<span class="c1">// Creates them if they don&#39;t exist</span></div><div class='line' id='LC177'>			<span class="k">if</span><span class="p">(</span><span class="nx">settings</span><span class="p">.</span><span class="nx">panelArrows</span><span class="p">)</span></div><div class='line' id='LC178'>				<span class="nx">positionPanelArrows</span><span class="p">();</span></div><div class='line' id='LC179'><br/></div><div class='line' id='LC180'>			<span class="k">if</span><span class="p">(</span><span class="nx">settings</span><span class="p">.</span><span class="nx">panelArrowsShowOnHover</span><span class="p">){</span></div><div class='line' id='LC181'>				<span class="k">if</span><span class="p">(</span><span class="nx">$leftPanelArrow</span><span class="p">)</span></div><div class='line' id='LC182'>					<span class="nx">$leftPanelArrow</span><span class="p">.</span><span class="nx">addClass</span><span class="p">(</span><span class="s1">&#39;showOnHover&#39;</span><span class="p">);</span></div><div class='line' id='LC183'>				<span class="k">if</span><span class="p">(</span><span class="nx">$rightPanelArrow</span><span class="p">)</span></div><div class='line' id='LC184'>					<span class="nx">$rightPanelArrow</span><span class="p">.</span><span class="nx">addClass</span><span class="p">(</span><span class="s1">&#39;showOnHover&#39;</span><span class="p">);</span></div><div class='line' id='LC185'>			<span class="p">}</span></div><div class='line' id='LC186'><br/></div><div class='line' id='LC187'>			<span class="nx">$contentDivsContainer</span><span class="p">.</span><span class="nx">resize</span><span class="p">(</span><span class="nx">positionPanelArrows</span><span class="p">);</span></div><div class='line' id='LC188'><br/></div><div class='line' id='LC189'>			<span class="c1">// Make responsive to changes in dimensions</span></div><div class='line' id='LC190'>			<span class="nx">$tabsListWrapper</span><span class="p">.</span><span class="nx">resize</span><span class="p">(</span><span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC191'>				<span class="nx">resizeTabsList</span><span class="p">();</span></div><div class='line' id='LC192'>				<span class="nx">resizePanels</span><span class="p">();</span></div><div class='line' id='LC193'>			<span class="p">});</span></div><div class='line' id='LC194'><br/></div><div class='line' id='LC195'>			<span class="c1">// Resize content container height if inner panels change</span></div><div class='line' id='LC196'>			<span class="nx">setInterval</span><span class="p">(</span><span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC197'>				<span class="kd">var</span> <span class="nx">$panel</span> <span class="o">=</span> <span class="nx">$contentDivsContainer</span><span class="p">.</span><span class="nx">children</span><span class="p">(</span><span class="s1">&#39;.selected&#39;</span><span class="p">);</span></div><div class='line' id='LC198'>				<span class="k">if</span><span class="p">(</span><span class="nx">$panel</span><span class="p">.</span><span class="nx">outerHeight</span><span class="p">()</span> <span class="o">&gt;</span> <span class="nx">$contentDivsContainer</span><span class="p">.</span><span class="nx">outerHeight</span><span class="p">()</span> <span class="o">&amp;&amp;</span> <span class="nx">heightLock</span><span class="p">)</span></div><div class='line' id='LC199'>					<span class="nx">resizeContentContainer</span><span class="p">(</span><span class="nx">$panel</span><span class="p">);</span></div><div class='line' id='LC200'>			<span class="p">},</span> <span class="mi">100</span><span class="p">);</span></div><div class='line' id='LC201'><br/></div><div class='line' id='LC202'>			<span class="nx">resizeTabsList</span><span class="p">();</span></div><div class='line' id='LC203'><br/></div><div class='line' id='LC204'>			<span class="c1">// Hide tabs wrapper if option if false</span></div><div class='line' id='LC205'>			<span class="k">if</span><span class="p">(</span><span class="o">!</span><span class="nx">settings</span><span class="p">.</span><span class="nx">tabs</span><span class="p">)</span></div><div class='line' id='LC206'>				<span class="nx">$tabsListWrapper</span><span class="p">.</span><span class="nx">hide</span><span class="p">();</span></div><div class='line' id='LC207'><br/></div><div class='line' id='LC208'>			<span class="c1">// Auto play</span></div><div class='line' id='LC209'>			<span class="k">if</span><span class="p">(</span><span class="nx">settings</span><span class="p">.</span><span class="nx">autoplay</span><span class="p">)</span></div><div class='line' id='LC210'>				<span class="nx">setInterval</span><span class="p">(</span><span class="nx">plugin</span><span class="p">.</span><span class="nx">next</span><span class="p">,</span> <span class="nx">settings</span><span class="p">.</span><span class="nx">autoplay</span><span class="p">);</span></div><div class='line' id='LC211'><br/></div><div class='line' id='LC212'>			<span class="c1">// Panel arrows</span></div><div class='line' id='LC213'><br/></div><div class='line' id='LC214'>			<span class="c1">// Mousehweel</span></div><div class='line' id='LC215'>			<span class="nx">$container</span><span class="p">.</span><span class="nx">bind</span><span class="p">(</span><span class="s1">&#39;mousewheel&#39;</span><span class="p">,</span> <span class="kd">function</span><span class="p">(</span><span class="nx">event</span><span class="p">,</span> <span class="nx">delta</span><span class="p">,</span> <span class="nx">deltaX</span><span class="p">,</span> <span class="nx">deltaY</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC216'>			    <span class="k">if</span><span class="p">(</span><span class="nx">delta</span> <span class="o">&gt;</span> <span class="mi">0</span><span class="p">)</span></div><div class='line' id='LC217'>			    	<span class="nx">plugin</span><span class="p">.</span><span class="nx">next</span><span class="p">();</span></div><div class='line' id='LC218'>			   	<span class="k">else</span> <span class="k">if</span><span class="p">(</span><span class="nx">delta</span> <span class="o">&lt;</span> <span class="mi">0</span><span class="p">)</span></div><div class='line' id='LC219'>			   		<span class="nx">plugin</span><span class="p">.</span><span class="nx">prev</span><span class="p">();</span></div><div class='line' id='LC220'>			   	<span class="k">return</span> <span class="kc">false</span><span class="p">;</span></div><div class='line' id='LC221'>			<span class="p">});</span></div><div class='line' id='LC222'>		<span class="p">}</span></div><div class='line' id='LC223'><br/></div><div class='line' id='LC224'>		<span class="cm">/*</span></div><div class='line' id='LC225'><span class="cm">		 * Public methods</span></div><div class='line' id='LC226'><span class="cm">		 */</span></div><div class='line' id='LC227'><br/></div><div class='line' id='LC228'>		<span class="c1">// Select tab</span></div><div class='line' id='LC229'>		<span class="c1">// param: tab is a tab index (1 ... n) or jQuery object of tab li element</span></div><div class='line' id='LC230'>		<span class="nx">plugin</span><span class="p">.</span><span class="nx">selectTab</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(</span><span class="nx">tab</span><span class="p">){</span></div><div class='line' id='LC231'>			<span class="nx">heightLock</span> <span class="o">=</span> <span class="kc">false</span><span class="p">;</span></div><div class='line' id='LC232'><br/></div><div class='line' id='LC233'>			<span class="c1">// Find $targetPanel, the panel to show</span></div><div class='line' id='LC234'>			<span class="kd">var</span> <span class="nx">$clicked</span> <span class="o">=</span> <span class="p">(</span><span class="k">typeof</span> <span class="nx">tab</span> <span class="o">===</span> <span class="s1">&#39;number&#39;</span><span class="p">)</span> <span class="o">?</span> <span class="nx">$tabsList</span><span class="p">.</span><span class="nx">children</span><span class="p">(</span><span class="s2">&quot;li:nth-child(&quot;</span><span class="o">+</span><span class="nx">tab</span><span class="o">+</span><span class="s2">&quot;)&quot;</span><span class="p">)</span> <span class="o">:</span> <span class="nx">tab</span><span class="p">;</span></div><div class='line' id='LC235'>			<span class="kd">var</span> <span class="nx">targetId</span> <span class="o">=</span> <span class="p">(</span><span class="nx">$clicked</span><span class="p">.</span><span class="nx">find</span><span class="p">(</span><span class="s1">&#39;a&#39;</span><span class="p">).</span><span class="nx">attr</span><span class="p">(</span><span class="s1">&#39;href&#39;</span><span class="p">)).</span><span class="nx">substr</span><span class="p">(</span><span class="mi">1</span><span class="p">);</span></div><div class='line' id='LC236'>			<span class="kd">var</span> <span class="nx">$targetPanel</span> <span class="o">=</span> <span class="nx">$contentDivsContainer</span><span class="p">.</span><span class="nx">children</span><span class="p">(</span><span class="s2">&quot;#&quot;</span><span class="o">+</span><span class="nx">targetId</span><span class="p">);</span></div><div class='line' id='LC237'><br/></div><div class='line' id='LC238'>			<span class="c1">// Update selected tab</span></div><div class='line' id='LC239'>			<span class="nx">plugin</span><span class="p">.</span><span class="nx">selectedTab</span> <span class="o">=</span> <span class="p">(</span><span class="k">typeof</span> <span class="nx">tab</span> <span class="o">===</span> <span class="s1">&#39;number&#39;</span><span class="p">)</span> <span class="o">?</span> <span class="nx">tab</span> <span class="o">:</span> <span class="nx">tab</span><span class="p">.</span><span class="nx">index</span><span class="p">()</span><span class="o">+</span><span class="mi">1</span><span class="p">;</span></div><div class='line' id='LC240'><br/></div><div class='line' id='LC241'>			<span class="c1">// Resize the main contant container to the size of $targetPanel</span></div><div class='line' id='LC242'>			<span class="nx">resizeContentContainer</span><span class="p">(</span><span class="nx">$targetPanel</span><span class="p">);</span></div><div class='line' id='LC243'><br/></div><div class='line' id='LC244'>			<span class="c1">// Lock selections until transitions finished</span></div><div class='line' id='LC245'>			<span class="nx">selectLock</span> <span class="o">=</span> <span class="kc">true</span><span class="p">;</span></div><div class='line' id='LC246'><br/></div><div class='line' id='LC247'>			<span class="c1">// Direction to slide panel on hide</span></div><div class='line' id='LC248'>			<span class="kd">var</span> <span class="nx">direction</span> <span class="o">=</span> <span class="p">(</span><span class="nx">$tabsList</span><span class="p">.</span><span class="nx">find</span><span class="p">(</span><span class="s1">&#39;.selected&#39;</span><span class="p">).</span><span class="nx">index</span><span class="p">()</span> <span class="o">&lt;</span> <span class="nx">$clicked</span><span class="p">.</span><span class="nx">index</span><span class="p">())</span> <span class="o">?</span> <span class="s1">&#39;left&#39;</span> <span class="o">:</span> <span class="s1">&#39;right&#39;</span><span class="p">;</span></div><div class='line' id='LC249'><br/></div><div class='line' id='LC250'>			<span class="c1">// Update selected classes</span></div><div class='line' id='LC251'>			<span class="nx">$clicked</span><span class="p">.</span><span class="nx">siblings</span><span class="p">().</span><span class="nx">removeClass</span><span class="p">(</span><span class="s1">&#39;selected&#39;</span><span class="p">);</span></div><div class='line' id='LC252'>			<span class="k">if</span><span class="p">(</span><span class="nx">settings</span><span class="p">.</span><span class="nx">classes</span><span class="p">.</span><span class="nx">tabActive</span> <span class="o">!=</span> <span class="s1">&#39;&#39;</span><span class="p">)</span> <span class="nx">$clicked</span><span class="p">.</span><span class="nx">siblings</span><span class="p">().</span><span class="nx">removeClass</span><span class="p">(</span><span class="nx">settings</span><span class="p">.</span><span class="nx">classes</span><span class="p">.</span><span class="nx">tabActive</span><span class="p">);</span></div><div class='line' id='LC253'>			<span class="nx">$clicked</span><span class="p">.</span><span class="nx">addClass</span><span class="p">(</span><span class="s1">&#39;selected&#39;</span><span class="p">).</span><span class="nx">addClass</span><span class="p">(</span><span class="nx">settings</span><span class="p">.</span><span class="nx">classes</span><span class="p">.</span><span class="nx">tabActive</span><span class="p">);</span></div><div class='line' id='LC254'><br/></div><div class='line' id='LC255'>			<span class="c1">// Hide and show appropriate panels</span></div><div class='line' id='LC256'>			<span class="nx">hidePanel</span><span class="p">(</span><span class="nx">$contentDivsContainer</span><span class="p">.</span><span class="nx">children</span><span class="p">(</span><span class="s2">&quot;.ui-slider-tab-content:visible&quot;</span><span class="p">),</span> <span class="nx">direction</span><span class="p">);</span></div><div class='line' id='LC257'>			<span class="nx">showPanel</span><span class="p">(</span><span class="nx">$targetPanel</span><span class="p">);</span></div><div class='line' id='LC258'><br/></div><div class='line' id='LC259'>			<span class="c1">// Slide tabs so that they fit in $tabsListContainer</span></div><div class='line' id='LC260'>			<span class="nx">fitTabInContainer</span><span class="p">(</span><span class="nx">$clicked</span><span class="p">);</span></div><div class='line' id='LC261'><br/></div><div class='line' id='LC262'>			<span class="c1">// Select the proper indicator</span></div><div class='line' id='LC263'>			<span class="nx">selectIndicator</span><span class="p">();</span></div><div class='line' id='LC264'>		<span class="p">};</span></div><div class='line' id='LC265'><br/></div><div class='line' id='LC266'>		<span class="c1">// Select the next (right) panel</span></div><div class='line' id='LC267'>		<span class="nx">plugin</span><span class="p">.</span><span class="nx">next</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC268'>			<span class="k">if</span><span class="p">(</span><span class="o">!</span><span class="nx">selectLock</span><span class="p">){</span></div><div class='line' id='LC269'>				<span class="k">if</span><span class="p">(</span><span class="nx">plugin</span><span class="p">.</span><span class="nx">count</span> <span class="o">===</span> <span class="nx">plugin</span><span class="p">.</span><span class="nx">selectedTab</span><span class="p">)</span></div><div class='line' id='LC270'>					<span class="nx">plugin</span><span class="p">.</span><span class="nx">selectTab</span><span class="p">(</span><span class="mi">1</span><span class="p">);</span></div><div class='line' id='LC271'>				<span class="k">else</span> <span class="nx">plugin</span><span class="p">.</span><span class="nx">selectTab</span><span class="p">(</span><span class="nx">plugin</span><span class="p">.</span><span class="nx">selectedTab</span><span class="o">+</span><span class="mi">1</span><span class="p">);</span></div><div class='line' id='LC272'>			<span class="p">}</span>	</div><div class='line' id='LC273'>		<span class="p">};</span></div><div class='line' id='LC274'><br/></div><div class='line' id='LC275'>		<span class="c1">// Select the previous panel</span></div><div class='line' id='LC276'>		<span class="nx">plugin</span><span class="p">.</span><span class="nx">prev</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC277'>			<span class="k">if</span><span class="p">(</span><span class="o">!</span><span class="nx">selectLock</span><span class="p">){</span></div><div class='line' id='LC278'>				<span class="k">if</span><span class="p">(</span><span class="nx">plugin</span><span class="p">.</span><span class="nx">selectedTab</span> <span class="o">===</span> <span class="mi">1</span><span class="p">)</span></div><div class='line' id='LC279'>					<span class="nx">plugin</span><span class="p">.</span><span class="nx">selectTab</span><span class="p">(</span><span class="nx">plugin</span><span class="p">.</span><span class="nx">count</span><span class="p">);</span></div><div class='line' id='LC280'>				<span class="k">else</span> <span class="nx">plugin</span><span class="p">.</span><span class="nx">selectTab</span><span class="p">(</span><span class="nx">plugin</span><span class="p">.</span><span class="nx">selectedTab</span><span class="o">-</span><span class="mi">1</span><span class="p">);</span></div><div class='line' id='LC281'>			<span class="p">}</span></div><div class='line' id='LC282'>		<span class="p">};</span></div><div class='line' id='LC283'><br/></div><div class='line' id='LC284'>		<span class="c1">// Slide tabs left/right within $tabsListContainer</span></div><div class='line' id='LC285'>		<span class="nx">plugin</span><span class="p">.</span><span class="nx">slideTabs</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(</span><span class="nx">direction</span><span class="p">,</span> <span class="nx">length</span><span class="p">){</span></div><div class='line' id='LC286'>			<span class="kd">var</span> <span class="nx">margin</span> <span class="o">=</span> <span class="nb">parseInt</span><span class="p">(</span><span class="nx">$tabsList</span><span class="p">.</span><span class="nx">css</span><span class="p">(</span><span class="s1">&#39;margin-left&#39;</span><span class="p">));</span></div><div class='line' id='LC287'>			<span class="kd">var</span> <span class="nx">newMargin</span> <span class="o">=</span> <span class="nx">margin</span><span class="p">;</span></div><div class='line' id='LC288'><br/></div><div class='line' id='LC289'>			<span class="c1">// Reset &#39;edge&#39; classes on tab arrows</span></div><div class='line' id='LC290'>			<span class="nx">$leftTabArrow</span><span class="p">.</span><span class="nx">removeClass</span><span class="p">(</span><span class="s1">&#39;edge&#39;</span><span class="p">);</span></div><div class='line' id='LC291'>			<span class="nx">$rightTabArrow</span><span class="p">.</span><span class="nx">removeClass</span><span class="p">(</span><span class="s1">&#39;edge&#39;</span><span class="p">);</span></div><div class='line' id='LC292'><br/></div><div class='line' id='LC293'>			<span class="c1">// Calculate delta to slide by</span></div><div class='line' id='LC294'>			<span class="k">if</span><span class="p">(</span><span class="nx">direction</span><span class="o">==</span><span class="s1">&#39;right&#39;</span><span class="p">)</span> <span class="nx">newMargin</span> <span class="o">+=</span> <span class="nx">length</span><span class="p">;</span></div><div class='line' id='LC295'>			<span class="k">else</span> <span class="k">if</span><span class="p">(</span><span class="nx">direction</span><span class="o">==</span><span class="s1">&#39;left&#39;</span><span class="p">)</span> <span class="nx">newMargin</span> <span class="o">-=</span> <span class="nx">length</span><span class="p">;</span></div><div class='line' id='LC296'>			<span class="k">if</span><span class="p">(</span><span class="nx">newMargin</span> <span class="o">&gt;=</span> <span class="mi">0</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC297'>				<span class="nx">newMargin</span> <span class="o">=</span> <span class="mi">0</span><span class="p">;</span></div><div class='line' id='LC298'>				<span class="nx">$leftTabArrow</span><span class="p">.</span><span class="nx">addClass</span><span class="p">(</span><span class="s1">&#39;edge&#39;</span><span class="p">);</span></div><div class='line' id='LC299'>			<span class="p">}</span></div><div class='line' id='LC300'>			<span class="k">else</span> <span class="k">if</span><span class="p">(</span><span class="nx">newMargin</span> <span class="o">&lt;=</span> <span class="nx">minMargin</span><span class="p">){</span></div><div class='line' id='LC301'>				<span class="nx">newMargin</span> <span class="o">=</span> <span class="nx">minMargin</span><span class="p">;</span></div><div class='line' id='LC302'>				<span class="nx">$rightTabArrow</span><span class="p">.</span><span class="nx">addClass</span><span class="p">(</span><span class="s1">&#39;edge&#39;</span><span class="p">);</span></div><div class='line' id='LC303'>			<span class="p">}</span></div><div class='line' id='LC304'><br/></div><div class='line' id='LC305'>			<span class="c1">// Animate</span></div><div class='line' id='LC306'>			<span class="nx">$tabsList</span><span class="p">.</span><span class="nx">animate</span><span class="p">({</span><span class="s1">&#39;margin-left&#39;</span><span class="o">:</span> <span class="nx">newMargin</span><span class="p">},</span> <span class="nx">settings</span><span class="p">.</span><span class="nx">tabSlideSpeed</span><span class="p">);</span></div><div class='line' id='LC307'>		<span class="p">};</span></div><div class='line' id='LC308'><br/></div><div class='line' id='LC309'>		<span class="c1">// Show panel indicators</span></div><div class='line' id='LC310'>		<span class="c1">// Create indicators if they don&#39;t exist yet</span></div><div class='line' id='LC311'>		<span class="nx">plugin</span><span class="p">.</span><span class="nx">showIndicators</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC312'>			<span class="k">if</span><span class="p">(</span><span class="o">!</span><span class="nx">$indicators</span><span class="p">){</span></div><div class='line' id='LC313'>				<span class="nx">$indicators</span> <span class="o">=</span> <span class="nx">$</span><span class="p">(</span><span class="s2">&quot;&lt;div class=&#39;ui-slider-tabs-indicator-container&#39;&gt;&quot;</span><span class="p">);</span></div><div class='line' id='LC314'>				<span class="k">for</span><span class="p">(</span><span class="kd">var</span> <span class="nx">i</span> <span class="o">=</span> <span class="mi">0</span><span class="p">;</span> <span class="nx">i</span> <span class="o">&lt;</span> <span class="nx">$contentDivs</span><span class="p">.</span><span class="nx">length</span><span class="p">;</span> <span class="nx">i</span><span class="o">++</span><span class="p">){</span></div><div class='line' id='LC315'>					<span class="nx">$indicators</span><span class="p">.</span><span class="nx">append</span><span class="p">(</span><span class="s2">&quot;&lt;div class=&#39;ui-slider-tabs-indicator&#39;&gt;&lt;/div&gt;&quot;</span><span class="p">);</span></div><div class='line' id='LC316'>				<span class="p">}</span></div><div class='line' id='LC317'>				<span class="nx">$contentDivsContainer</span><span class="p">.</span><span class="nx">append</span><span class="p">(</span><span class="nx">$indicators</span><span class="p">);</span></div><div class='line' id='LC318'>			<span class="p">}</span></div><div class='line' id='LC319'>			<span class="k">else</span></div><div class='line' id='LC320'>				<span class="nx">$indicators</span><span class="p">.</span><span class="nx">show</span><span class="p">();</span></div><div class='line' id='LC321'>		<span class="p">};</span></div><div class='line' id='LC322'><br/></div><div class='line' id='LC323'>		<span class="c1">// Hide panel indicators</span></div><div class='line' id='LC324'>		<span class="nx">plugin</span><span class="p">.</span><span class="nx">hideIndicators</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC325'>			<span class="k">if</span><span class="p">(</span><span class="nx">$indicators</span><span class="p">)</span></div><div class='line' id='LC326'>				<span class="nx">$indicators</span><span class="p">.</span><span class="nx">hide</span><span class="p">();</span></div><div class='line' id='LC327'>		<span class="p">};</span></div><div class='line' id='LC328'><br/></div><div class='line' id='LC329'>		<span class="c1">// Show arrows that slide tabs left and right</span></div><div class='line' id='LC330'>		<span class="nx">plugin</span><span class="p">.</span><span class="nx">showTabArrows</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC331'>			<span class="k">if</span><span class="p">(</span><span class="o">!</span><span class="nx">settings</span><span class="p">.</span><span class="nx">tabArrows</span><span class="p">)</span></div><div class='line' id='LC332'>				<span class="k">return</span><span class="p">;</span></div><div class='line' id='LC333'>			<span class="nx">$leftTabArrow</span><span class="p">.</span><span class="nx">show</span><span class="p">();</span></div><div class='line' id='LC334'>			<span class="nx">$rightTabArrow</span><span class="p">.</span><span class="nx">show</span><span class="p">();</span></div><div class='line' id='LC335'>			<span class="nx">$tabsListContainer</span><span class="p">.</span><span class="nx">css</span><span class="p">(</span><span class="s1">&#39;margin&#39;</span><span class="p">,</span> <span class="s1">&#39;0 &#39;</span><span class="o">+</span><span class="nx">settings</span><span class="p">.</span><span class="nx">tabArrowWidth</span><span class="o">+</span><span class="s1">&#39;px&#39;</span><span class="p">);</span></div><div class='line' id='LC336'>		<span class="p">};</span></div><div class='line' id='LC337'><br/></div><div class='line' id='LC338'>		<span class="c1">// Hide arrows that slide tabs left and right</span></div><div class='line' id='LC339'>		<span class="nx">plugin</span><span class="p">.</span><span class="nx">hideTabArrows</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC340'>			<span class="nx">$leftTabArrow</span><span class="p">.</span><span class="nx">hide</span><span class="p">();</span></div><div class='line' id='LC341'>			<span class="nx">$rightTabArrow</span><span class="p">.</span><span class="nx">hide</span><span class="p">();</span></div><div class='line' id='LC342'>			<span class="nx">$tabsListContainer</span><span class="p">.</span><span class="nx">css</span><span class="p">(</span><span class="s1">&#39;margin&#39;</span><span class="p">,</span> <span class="s1">&#39;0&#39;</span><span class="p">);</span></div><div class='line' id='LC343'>		<span class="p">};</span></div><div class='line' id='LC344'><br/></div><div class='line' id='LC345'>		<span class="c1">// Show panel arrows</span></div><div class='line' id='LC346'>		<span class="nx">plugin</span><span class="p">.</span><span class="nx">showPanelArrows</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC347'>			<span class="k">if</span><span class="p">(</span><span class="nx">$leftPanelArrow</span><span class="p">)</span> <span class="nx">$leftPanelArrow</span><span class="p">.</span><span class="nx">show</span><span class="p">();</span></div><div class='line' id='LC348'>			<span class="k">if</span><span class="p">(</span><span class="nx">$rightPanelArrow</span><span class="p">)</span> <span class="nx">$rightPanelArrow</span><span class="p">.</span><span class="nx">show</span><span class="p">();</span></div><div class='line' id='LC349'>		<span class="p">};</span></div><div class='line' id='LC350'><br/></div><div class='line' id='LC351'>		<span class="c1">// Hide panel arrows</span></div><div class='line' id='LC352'>		<span class="nx">plugin</span><span class="p">.</span><span class="nx">hidePanelArrows</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC353'>			<span class="k">if</span><span class="p">(</span><span class="nx">$leftPanelArrow</span><span class="p">)</span> <span class="nx">$leftPanelArrow</span><span class="p">.</span><span class="nx">hide</span><span class="p">();</span></div><div class='line' id='LC354'>			<span class="k">if</span><span class="p">(</span><span class="nx">$rightPanelArrow</span><span class="p">)</span> <span class="nx">$rightPanelArrow</span><span class="p">.</span><span class="nx">hide</span><span class="p">();</span></div><div class='line' id='LC355'>		<span class="p">};</span></div><div class='line' id='LC356'><br/></div><div class='line' id='LC357'>		<span class="cm">/*</span></div><div class='line' id='LC358'><span class="cm">		 * Private methods</span></div><div class='line' id='LC359'><span class="cm">		 */</span></div><div class='line' id='LC360'><br/></div><div class='line' id='LC361'>		<span class="c1">// Add the selected class to the plugin.selectedTab tab. Remove from all others.</span></div><div class='line' id='LC362'>		<span class="kd">var</span> <span class="nx">selectIndicator</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC363'>			<span class="k">if</span><span class="p">(</span><span class="nx">settings</span><span class="p">.</span><span class="nx">indicators</span> <span class="o">&amp;&amp;</span> <span class="nx">$indicators</span><span class="p">){</span></div><div class='line' id='LC364'>				<span class="kd">var</span> <span class="nx">$indicator</span> <span class="o">=</span> <span class="nx">$indicators</span><span class="p">.</span><span class="nx">children</span><span class="p">(</span><span class="s2">&quot;div:nth-child(&quot;</span><span class="o">+</span><span class="nx">plugin</span><span class="p">.</span><span class="nx">selectedTab</span><span class="o">+</span><span class="s2">&quot;)&quot;</span><span class="p">);</span></div><div class='line' id='LC365'>				<span class="nx">$indicator</span><span class="p">.</span><span class="nx">siblings</span><span class="p">().</span><span class="nx">removeClass</span><span class="p">(</span><span class="s1">&#39;selected&#39;</span><span class="p">);</span></div><div class='line' id='LC366'>				<span class="nx">$indicator</span><span class="p">.</span><span class="nx">addClass</span><span class="p">(</span><span class="s1">&#39;selected&#39;</span><span class="p">);</span></div><div class='line' id='LC367'>			<span class="p">}</span>	</div><div class='line' id='LC368'>		<span class="p">};</span></div><div class='line' id='LC369'><br/></div><div class='line' id='LC370'>		<span class="c1">// 	Slide tabs inside of $tabsListContainer so that the selected one fits inside</span></div><div class='line' id='LC371'>		<span class="kd">var</span> <span class="nx">fitTabInContainer</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(</span><span class="nx">tab</span><span class="p">){</span></div><div class='line' id='LC372'>			<span class="kd">var</span> <span class="nx">tabOffset</span> <span class="o">=</span> <span class="nx">tab</span><span class="p">.</span><span class="nx">offset</span><span class="p">(),</span></div><div class='line' id='LC373'>				<span class="nx">containerOffset</span> <span class="o">=</span> <span class="nx">$tabsListContainer</span><span class="p">.</span><span class="nx">offset</span><span class="p">(),</span></div><div class='line' id='LC374'>				<span class="nx">leftOffset</span> <span class="o">=</span> <span class="nx">tabOffset</span><span class="p">.</span><span class="nx">left</span> <span class="o">-</span> <span class="nx">containerOffset</span><span class="p">.</span><span class="nx">left</span><span class="p">,</span></div><div class='line' id='LC375'>				<span class="nx">rightOffset</span> <span class="o">=</span> <span class="p">(</span><span class="nx">containerOffset</span><span class="p">.</span><span class="nx">left</span> <span class="o">+</span> <span class="nx">$tabsListContainer</span><span class="p">.</span><span class="nx">outerWidth</span><span class="p">())</span> <span class="o">-</span> <span class="p">(</span><span class="nx">tabOffset</span><span class="p">.</span><span class="nx">left</span> <span class="o">+</span> <span class="nx">tab</span><span class="p">.</span><span class="nx">outerWidth</span><span class="p">()</span> <span class="p">);</span></div><div class='line' id='LC376'><br/></div><div class='line' id='LC377'>			<span class="k">if</span><span class="p">(</span><span class="nx">leftOffset</span> <span class="o">&lt;</span> <span class="mi">0</span><span class="p">)</span></div><div class='line' id='LC378'>				<span class="nx">plugin</span><span class="p">.</span><span class="nx">slideTabs</span><span class="p">(</span><span class="s1">&#39;right&#39;</span><span class="p">,</span> <span class="o">-</span><span class="nx">leftOffset</span><span class="p">);</span></div><div class='line' id='LC379'>			<span class="k">else</span> <span class="k">if</span><span class="p">(</span><span class="nx">rightOffset</span> <span class="o">&lt;</span> <span class="mi">0</span><span class="p">)</span></div><div class='line' id='LC380'>				<span class="nx">plugin</span><span class="p">.</span><span class="nx">slideTabs</span><span class="p">(</span><span class="s1">&#39;left&#39;</span><span class="p">,</span> <span class="o">-</span><span class="nx">rightOffset</span><span class="p">);</span></div><div class='line' id='LC381'>		<span class="p">};</span></div><div class='line' id='LC382'><br/></div><div class='line' id='LC383'>		<span class="c1">// Reposition content panels so that they are ready to be transitioned in and out.</span></div><div class='line' id='LC384'>		<span class="c1">// This depends on whether the transition is set to slide or fade</span></div><div class='line' id='LC385'>		<span class="kd">var</span> <span class="nx">reorderPanels</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC386'>			<span class="c1">// Position content divs</span></div><div class='line' id='LC387'>			<span class="k">if</span><span class="p">(</span><span class="nx">settings</span><span class="p">.</span><span class="nx">transition</span> <span class="o">==</span> <span class="s1">&#39;slide&#39;</span><span class="p">)</span></div><div class='line' id='LC388'>				<span class="c1">// Move panels left/right basedon their index relative to the selected panel</span></div><div class='line' id='LC389'>				<span class="nx">$tabsList</span><span class="p">.</span><span class="nx">children</span><span class="p">(</span><span class="s1">&#39;li&#39;</span><span class="p">).</span><span class="nx">each</span><span class="p">(</span><span class="kd">function</span><span class="p">(</span><span class="nx">index</span><span class="p">,</span> <span class="nx">el</span><span class="p">){</span></div><div class='line' id='LC390'>					<span class="kd">var</span> <span class="nx">selectedIndex</span> <span class="o">=</span> <span class="nx">$tabsList</span><span class="p">.</span><span class="nx">children</span><span class="p">(</span><span class="s1">&#39;.selected&#39;</span><span class="p">).</span><span class="nx">index</span><span class="p">(),</span></div><div class='line' id='LC391'>						<span class="nx">thisIndex</span> <span class="o">=</span> <span class="nx">$</span><span class="p">(</span><span class="nx">el</span><span class="p">).</span><span class="nx">index</span><span class="p">();</span></div><div class='line' id='LC392'>					<span class="kd">var</span> <span class="nx">panel</span> <span class="o">=</span> <span class="nx">$contentDivsContainer</span><span class="p">.</span><span class="nx">children</span><span class="p">(</span><span class="s1">&#39;#&#39;</span><span class="o">+</span><span class="nx">$</span><span class="p">(</span><span class="nx">el</span><span class="p">).</span><span class="nx">find</span><span class="p">(</span><span class="s1">&#39;a&#39;</span><span class="p">).</span><span class="nx">attr</span><span class="p">(</span><span class="s1">&#39;href&#39;</span><span class="p">).</span><span class="nx">substr</span><span class="p">(</span><span class="mi">1</span><span class="p">));</span></div><div class='line' id='LC393'>					<span class="k">if</span><span class="p">(</span><span class="nx">selectedIndex</span> <span class="o">&lt;</span> <span class="nx">thisIndex</span><span class="p">)</span></div><div class='line' id='LC394'>						<span class="nx">panel</span><span class="p">.</span><span class="nx">css</span><span class="p">({</span><span class="nx">left</span><span class="o">:</span> <span class="nx">$contentDivsContainer</span><span class="p">.</span><span class="nx">width</span><span class="p">()</span><span class="o">+</span><span class="s1">&#39;px&#39;</span><span class="p">});</span></div><div class='line' id='LC395'>					<span class="k">else</span> <span class="k">if</span><span class="p">(</span><span class="nx">selectedIndex</span> <span class="o">&gt;</span> <span class="nx">thisIndex</span><span class="p">)</span></div><div class='line' id='LC396'>						<span class="nx">panel</span><span class="p">.</span><span class="nx">css</span><span class="p">({</span><span class="nx">left</span><span class="o">:</span> <span class="s1">&#39;-&#39;</span><span class="o">+</span><span class="nx">$contentDivsContainer</span><span class="p">.</span><span class="nx">width</span><span class="p">()</span><span class="o">+</span><span class="s1">&#39;px&#39;</span><span class="p">});</span></div><div class='line' id='LC397'>					<span class="k">else</span></div><div class='line' id='LC398'>						<span class="nx">panel</span><span class="p">.</span><span class="nx">addClass</span><span class="p">(</span><span class="nx">settings</span><span class="p">.</span><span class="nx">classes</span><span class="p">.</span><span class="nx">panelActive</span><span class="p">);</span></div><div class='line' id='LC399'>				<span class="p">});</span></div><div class='line' id='LC400'><br/></div><div class='line' id='LC401'>			<span class="k">if</span><span class="p">(</span><span class="nx">settings</span><span class="p">.</span><span class="nx">transition</span> <span class="o">==</span> <span class="s1">&#39;fade&#39;</span><span class="p">)</span></div><div class='line' id='LC402'>				<span class="c1">// Set opacity to correct value for non selected panels.</span></div><div class='line' id='LC403'>				<span class="nx">$tabsList</span><span class="p">.</span><span class="nx">children</span><span class="p">(</span><span class="s1">&#39;li&#39;</span><span class="p">).</span><span class="nx">each</span><span class="p">(</span><span class="kd">function</span><span class="p">(</span><span class="nx">index</span><span class="p">,</span> <span class="nx">el</span><span class="p">){</span></div><div class='line' id='LC404'>					<span class="kd">var</span> <span class="nx">selectedIndex</span> <span class="o">=</span> <span class="nx">$tabsList</span><span class="p">.</span><span class="nx">children</span><span class="p">(</span><span class="s1">&#39;.selected&#39;</span><span class="p">).</span><span class="nx">index</span><span class="p">(),</span></div><div class='line' id='LC405'>						<span class="nx">thisIndex</span> <span class="o">=</span> <span class="nx">$</span><span class="p">(</span><span class="nx">el</span><span class="p">).</span><span class="nx">index</span><span class="p">();</span></div><div class='line' id='LC406'>					<span class="kd">var</span> <span class="nx">panel</span> <span class="o">=</span> <span class="nx">$contentDivsContainer</span><span class="p">.</span><span class="nx">children</span><span class="p">(</span><span class="s1">&#39;#&#39;</span><span class="o">+</span><span class="nx">$</span><span class="p">(</span><span class="nx">el</span><span class="p">).</span><span class="nx">find</span><span class="p">(</span><span class="s1">&#39;a&#39;</span><span class="p">).</span><span class="nx">attr</span><span class="p">(</span><span class="s1">&#39;href&#39;</span><span class="p">).</span><span class="nx">substr</span><span class="p">(</span><span class="mi">1</span><span class="p">));</span></div><div class='line' id='LC407'>					<span class="k">if</span><span class="p">(</span><span class="nx">selectedIndex</span> <span class="o">!=</span> <span class="nx">thisIndex</span><span class="p">)</span></div><div class='line' id='LC408'>						<span class="nx">panel</span><span class="p">.</span><span class="nx">css</span><span class="p">({</span><span class="nx">opacity</span><span class="o">:</span> <span class="mi">0</span><span class="p">});</span></div><div class='line' id='LC409'>					<span class="k">else</span></div><div class='line' id='LC410'>						<span class="nx">panel</span><span class="p">.</span><span class="nx">addClass</span><span class="p">(</span><span class="nx">settings</span><span class="p">.</span><span class="nx">classes</span><span class="p">.</span><span class="nx">panelActive</span><span class="p">);</span></div><div class='line' id='LC411'>				<span class="p">});</span></div><div class='line' id='LC412'>		<span class="p">};</span></div><div class='line' id='LC413'><br/></div><div class='line' id='LC414'>		<span class="c1">// Object determining css properties to be animated to based on various actions, transitions, and directions</span></div><div class='line' id='LC415'>		<span class="kd">var</span> <span class="nx">panelAnimationCSS</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(</span><span class="nx">width</span><span class="p">){</span></div><div class='line' id='LC416'>			<span class="k">return</span> <span class="p">{</span></div><div class='line' id='LC417'>					<span class="nx">hide</span><span class="o">:</span> <span class="p">{</span></div><div class='line' id='LC418'>						<span class="nx">slideleft</span><span class="o">:</span> <span class="p">{</span></div><div class='line' id='LC419'>							<span class="nx">left</span><span class="o">:</span> <span class="s1">&#39;-&#39;</span><span class="o">+</span><span class="nx">width</span><span class="o">+</span><span class="s1">&#39;px&#39;</span></div><div class='line' id='LC420'>						<span class="p">},</span></div><div class='line' id='LC421'>						<span class="nx">slideright</span><span class="o">:</span> <span class="p">{</span></div><div class='line' id='LC422'>							<span class="nx">left</span><span class="o">:</span> <span class="nx">width</span><span class="o">+</span><span class="s1">&#39;px&#39;</span></div><div class='line' id='LC423'>						<span class="p">},</span></div><div class='line' id='LC424'>						<span class="nx">fade</span><span class="o">:</span> <span class="p">{</span></div><div class='line' id='LC425'>							<span class="nx">opacity</span><span class="o">:</span> <span class="mi">0</span></div><div class='line' id='LC426'>						<span class="p">}</span></div><div class='line' id='LC427'>					<span class="p">},</span></div><div class='line' id='LC428'>					<span class="nx">show</span><span class="o">:</span> <span class="p">{</span></div><div class='line' id='LC429'>						<span class="nx">slide</span><span class="o">:</span> <span class="p">{</span></div><div class='line' id='LC430'>							<span class="nx">left</span><span class="o">:</span> <span class="mi">0</span></div><div class='line' id='LC431'>						<span class="p">},</span></div><div class='line' id='LC432'>						<span class="nx">fade</span><span class="o">:</span> <span class="p">{</span></div><div class='line' id='LC433'>							<span class="nx">opacity</span><span class="o">:</span> <span class="mi">1</span></div><div class='line' id='LC434'>						<span class="p">}</span></div><div class='line' id='LC435'>					<span class="p">}</span></div><div class='line' id='LC436'>				<span class="p">}</span></div><div class='line' id='LC437'>		<span class="p">};</span></div><div class='line' id='LC438'><br/></div><div class='line' id='LC439'>		<span class="c1">// Transition out the passed in panel.</span></div><div class='line' id='LC440'>		<span class="c1">// param: 	panel is the jQuery object of the panel to be hidden</span></div><div class='line' id='LC441'>		<span class="c1">//			direction is either &#39;left&#39; or &#39;right&#39; for sliding transitions</span></div><div class='line' id='LC442'>		<span class="kd">var</span> <span class="nx">hidePanel</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(</span><span class="nx">panel</span><span class="p">,</span> <span class="nx">direction</span><span class="p">){</span></div><div class='line' id='LC443'>			<span class="c1">// Calculate correct key in panelAnimationCSS</span></div><div class='line' id='LC444'>			<span class="k">if</span><span class="p">(</span><span class="nx">settings</span><span class="p">.</span><span class="nx">transition</span> <span class="o">==</span> <span class="s1">&#39;slide&#39;</span><span class="p">)</span></div><div class='line' id='LC445'>				<span class="kd">var</span> <span class="nx">trans</span> <span class="o">=</span> <span class="s1">&#39;slide&#39;</span><span class="o">+</span><span class="nx">direction</span><span class="p">;</span></div><div class='line' id='LC446'>			<span class="k">else</span> <span class="kd">var</span> <span class="nx">trans</span> <span class="o">=</span> <span class="nx">settings</span><span class="p">.</span><span class="nx">transition</span><span class="p">;</span></div><div class='line' id='LC447'><br/></div><div class='line' id='LC448'>			<span class="c1">// Animate the panel out</span></div><div class='line' id='LC449'>			<span class="nx">panel</span><span class="p">.</span><span class="nx">animate</span><span class="p">(</span><span class="nx">panelAnimationCSS</span><span class="p">(</span><span class="nx">$contentDivsContainer</span><span class="p">.</span><span class="nx">width</span><span class="p">())[</span><span class="s1">&#39;hide&#39;</span><span class="p">][</span><span class="nx">trans</span><span class="p">],</span> <span class="nx">settings</span><span class="p">.</span><span class="nx">transitionSpeed</span><span class="p">,</span> <span class="nx">settings</span><span class="p">.</span><span class="nx">transitionEasing</span><span class="p">,</span> <span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC450'>				<span class="nx">panel</span><span class="p">.</span><span class="nx">hide</span><span class="p">();</span></div><div class='line' id='LC451'>				<span class="nx">panel</span><span class="p">.</span><span class="nx">removeClass</span><span class="p">(</span><span class="s1">&#39;selected&#39;</span><span class="p">);</span></div><div class='line' id='LC452'>				<span class="c1">//if(settings.classes.panelActive != &#39;&#39;) panel.removeClass(settings.classes.panelActive);</span></div><div class='line' id='LC453'>				<span class="nx">selectLock</span> <span class="o">=</span> <span class="kc">false</span><span class="p">;</span></div><div class='line' id='LC454'>				<span class="nx">reorderPanels</span><span class="p">();</span></div><div class='line' id='LC455'>			<span class="p">});</span></div><div class='line' id='LC456'>		<span class="p">};</span></div><div class='line' id='LC457'><br/></div><div class='line' id='LC458'>		<span class="c1">// Transition in the parameter panel</span></div><div class='line' id='LC459'>		<span class="c1">// param: 	panel is the jQuery object of the panel to be shown</span></div><div class='line' id='LC460'>		<span class="kd">var</span> <span class="nx">showPanel</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(</span><span class="nx">panel</span><span class="p">){</span></div><div class='line' id='LC461'>			<span class="c1">// Show first</span></div><div class='line' id='LC462'>			<span class="nx">panel</span><span class="p">.</span><span class="nx">show</span><span class="p">();</span></div><div class='line' id='LC463'>			<span class="nx">panel</span><span class="p">.</span><span class="nx">addClass</span><span class="p">(</span><span class="nx">settings</span><span class="p">.</span><span class="nx">classes</span><span class="p">.</span><span class="nx">panelActive</span><span class="p">).</span><span class="nx">addClass</span><span class="p">(</span><span class="s1">&#39;selected&#39;</span><span class="p">);</span></div><div class='line' id='LC464'><br/></div><div class='line' id='LC465'>			<span class="c1">// Then animate css properties</span></div><div class='line' id='LC466'>			<span class="nx">panel</span><span class="p">.</span><span class="nx">animate</span><span class="p">(</span><span class="nx">panelAnimationCSS</span><span class="p">(</span><span class="nx">$contentDivsContainer</span><span class="p">.</span><span class="nx">width</span><span class="p">())[</span><span class="s1">&#39;show&#39;</span><span class="p">][</span><span class="nx">settings</span><span class="p">.</span><span class="nx">transition</span><span class="p">],</span> <span class="nx">settings</span><span class="p">.</span><span class="nx">transitionSpeed</span><span class="p">,</span> <span class="nx">settings</span><span class="p">.</span><span class="nx">transitionEasing</span><span class="p">,</span> <span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC467'>				<span class="nx">selectLock</span> <span class="o">=</span> <span class="kc">false</span><span class="p">;</span></div><div class='line' id='LC468'>				<span class="nx">heightLock</span> <span class="o">=</span> <span class="kc">true</span><span class="p">;</span></div><div class='line' id='LC469'>				<span class="nx">reorderPanels</span><span class="p">();</span></div><div class='line' id='LC470'>			<span class="p">});</span></div><div class='line' id='LC471'>		<span class="p">};</span></div><div class='line' id='LC472'><br/></div><div class='line' id='LC473'>		<span class="c1">// Animate the height of the content container to height target</span></div><div class='line' id='LC474'>		<span class="c1">// params:  target (int) is the new height</span></div><div class='line' id='LC475'>		<span class="kd">var</span> <span class="nx">resizeContentContainer</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(</span><span class="nx">target</span><span class="p">){</span></div><div class='line' id='LC476'>			<span class="k">if</span><span class="p">(</span><span class="o">!</span><span class="nx">settings</span><span class="p">.</span><span class="nx">height</span><span class="p">)</span></div><div class='line' id='LC477'>				<span class="nx">$contentDivsContainer</span><span class="p">.</span><span class="nx">animate</span><span class="p">({</span></div><div class='line' id='LC478'>					<span class="nx">height</span><span class="o">:</span> <span class="nx">actualHeight</span><span class="p">(</span><span class="nx">target</span><span class="p">)</span></div><div class='line' id='LC479'>				<span class="p">},</span> <span class="mi">200</span><span class="p">);</span></div><div class='line' id='LC480'>		<span class="p">};</span></div><div class='line' id='LC481'><br/></div><div class='line' id='LC482'>		<span class="c1">// Position the panel arrows</span></div><div class='line' id='LC483'>		<span class="kd">var</span> <span class="nx">positionPanelArrows</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC484'>			<span class="k">if</span><span class="p">(</span><span class="nx">settings</span><span class="p">.</span><span class="nx">panelArrows</span><span class="p">){</span></div><div class='line' id='LC485'>				<span class="c1">// Initialize them if you need to</span></div><div class='line' id='LC486'>				<span class="k">if</span><span class="p">(</span><span class="o">!</span><span class="nx">$leftPanelArrow</span> <span class="o">&amp;&amp;</span> <span class="o">!</span><span class="nx">$rightPanelArrow</span><span class="p">){</span></div><div class='line' id='LC487'>					<span class="nx">$leftPanelArrow</span> <span class="o">=</span> <span class="nx">$</span><span class="p">(</span><span class="s2">&quot;&lt;div class=&#39;ui-slider-tabs-leftPanelArrow&#39;&gt;&quot;</span><span class="p">).</span><span class="nx">click</span><span class="p">(</span><span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC488'>						<span class="nx">plugin</span><span class="p">.</span><span class="nx">prev</span><span class="p">();</span></div><div class='line' id='LC489'>					<span class="p">});</span></div><div class='line' id='LC490'>					<span class="nx">$rightPanelArrow</span> <span class="o">=</span> <span class="nx">$</span><span class="p">(</span><span class="s2">&quot;&lt;div class=&#39;ui-slider-tabs-rightPanelArrow&#39;&gt;&quot;</span><span class="p">).</span><span class="nx">click</span><span class="p">(</span><span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC491'>						<span class="nx">plugin</span><span class="p">.</span><span class="nx">next</span><span class="p">();</span></div><div class='line' id='LC492'>					<span class="p">});</span></div><div class='line' id='LC493'><br/></div><div class='line' id='LC494'>					<span class="nx">$leftPanelArrow</span><span class="p">.</span><span class="nx">appendTo</span><span class="p">(</span><span class="nx">$contentDivsContainer</span><span class="p">);</span></div><div class='line' id='LC495'>					<span class="nx">$rightPanelArrow</span><span class="p">.</span><span class="nx">appendTo</span><span class="p">(</span><span class="nx">$contentDivsContainer</span><span class="p">);</span></div><div class='line' id='LC496'>				<span class="p">}</span></div><div class='line' id='LC497'><br/></div><div class='line' id='LC498'>				<span class="c1">// Set correct CSS &#39;top&#39; attribute of each panel arrow</span></div><div class='line' id='LC499'>				<span class="nx">$rightPanelArrow</span><span class="p">.</span><span class="nx">css</span><span class="p">({</span></div><div class='line' id='LC500'>					<span class="s2">&quot;top&quot;</span><span class="o">:</span> <span class="nx">$contentDivsContainer</span><span class="p">.</span><span class="nx">height</span><span class="p">()</span><span class="o">/</span><span class="mi">2</span> <span class="o">-</span> <span class="nx">$rightPanelArrow</span><span class="p">.</span><span class="nx">outerHeight</span><span class="p">()</span><span class="o">/</span><span class="mi">2</span></div><div class='line' id='LC501'>				<span class="p">});</span></div><div class='line' id='LC502'>				<span class="nx">$leftPanelArrow</span><span class="p">.</span><span class="nx">css</span><span class="p">({</span></div><div class='line' id='LC503'>					<span class="s2">&quot;top&quot;</span><span class="o">:</span> <span class="nx">$contentDivsContainer</span><span class="p">.</span><span class="nx">height</span><span class="p">()</span><span class="o">/</span><span class="mi">2</span> <span class="o">-</span> <span class="nx">$leftPanelArrow</span><span class="p">.</span><span class="nx">outerHeight</span><span class="p">()</span><span class="o">/</span><span class="mi">2</span></div><div class='line' id='LC504'>				<span class="p">});</span></div><div class='line' id='LC505'>			<span class="p">}</span></div><div class='line' id='LC506'>		<span class="p">};</span></div><div class='line' id='LC507'><br/></div><div class='line' id='LC508'>		<span class="c1">// Change the width of $tabsList to the sum of the outer widths of all tabs</span></div><div class='line' id='LC509'>		<span class="kd">var</span> <span class="nx">resizeTabsList</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC510'>			<span class="c1">// Calculate total width</span></div><div class='line' id='LC511'>			<span class="kd">var</span> <span class="nx">width</span> <span class="o">=</span> <span class="mi">0</span><span class="p">;</span></div><div class='line' id='LC512'>			<span class="nx">$tabsList</span><span class="p">.</span><span class="nx">children</span><span class="p">().</span><span class="nx">each</span><span class="p">(</span><span class="kd">function</span><span class="p">(</span><span class="nx">index</span><span class="p">,</span> <span class="nx">element</span><span class="p">){</span></div><div class='line' id='LC513'>				<span class="nx">width</span> <span class="o">+=</span> <span class="nx">$</span><span class="p">(</span><span class="nx">element</span><span class="p">).</span><span class="nx">outerWidth</span><span class="p">(</span><span class="kc">true</span><span class="p">);</span></div><div class='line' id='LC514'>			<span class="p">});</span></div><div class='line' id='LC515'>			<span class="c1">// Set new width of $tabsList</span></div><div class='line' id='LC516'>			<span class="nx">$tabsList</span><span class="p">.</span><span class="nx">width</span><span class="p">(</span><span class="nx">width</span><span class="p">);</span></div><div class='line' id='LC517'><br/></div><div class='line' id='LC518'>			<span class="c1">// Update minMargin. Hide tab arrows if no overflow</span></div><div class='line' id='LC519'>			<span class="k">if</span><span class="p">(</span><span class="nx">$tabsListContainer</span><span class="p">.</span><span class="nx">width</span><span class="p">()</span> <span class="o">&lt;</span> <span class="nx">width</span> <span class="o">&amp;&amp;</span> <span class="nx">settings</span><span class="p">.</span><span class="nx">tabArrows</span><span class="p">){</span></div><div class='line' id='LC520'>				<span class="nx">plugin</span><span class="p">.</span><span class="nx">showTabArrows</span><span class="p">();</span></div><div class='line' id='LC521'>				<span class="nx">minMargin</span> <span class="o">=</span> <span class="nx">$tabsListContainer</span><span class="p">.</span><span class="nx">width</span><span class="p">()</span> <span class="o">-</span> <span class="nx">width</span><span class="p">;</span></div><div class='line' id='LC522'>			<span class="p">}</span></div><div class='line' id='LC523'>			<span class="k">else</span> <span class="nx">plugin</span><span class="p">.</span><span class="nx">hideTabArrows</span><span class="p">();</span></div><div class='line' id='LC524'>		<span class="p">}</span></div><div class='line' id='LC525'><br/></div><div class='line' id='LC526'>		<span class="c1">// Resize indiviual panels to the width of the new container</span></div><div class='line' id='LC527'>		<span class="kd">var</span> <span class="nx">resizePanels</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC528'>			<span class="nx">$contentDivs</span><span class="p">.</span><span class="nx">width</span><span class="p">(</span><span class="nx">$contentDivsContainer</span><span class="p">.</span><span class="nx">width</span><span class="p">()</span> <span class="o">-</span> <span class="p">(</span><span class="nx">$contentDivs</span><span class="p">.</span><span class="nx">outerWidth</span><span class="p">()</span> <span class="o">-</span> <span class="nx">$contentDivs</span><span class="p">.</span><span class="nx">width</span><span class="p">()));</span></div><div class='line' id='LC529'>		<span class="p">};</span></div><div class='line' id='LC530'><br/></div><div class='line' id='LC531'>		<span class="c1">// Get height of a hidden element</span></div><div class='line' id='LC532'>		<span class="kd">var</span> <span class="nx">actualHeight</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(</span><span class="nx">element</span><span class="p">){</span></div><div class='line' id='LC533'>			<span class="kd">var</span> <span class="nx">prevCSS</span> <span class="o">=</span> <span class="p">{</span></div><div class='line' id='LC534'>				<span class="s1">&#39;display&#39;</span><span class="o">:</span> <span class="nx">element</span><span class="p">.</span><span class="nx">css</span><span class="p">(</span><span class="s1">&#39;display&#39;</span><span class="p">),</span></div><div class='line' id='LC535'>				<span class="s1">&#39;left&#39;</span><span class="o">:</span> <span class="nx">element</span><span class="p">.</span><span class="nx">css</span><span class="p">(</span><span class="s1">&#39;left&#39;</span><span class="p">),</span></div><div class='line' id='LC536'>				<span class="s1">&#39;position&#39;</span><span class="o">:</span> <span class="nx">element</span><span class="p">.</span><span class="nx">css</span><span class="p">(</span><span class="s1">&#39;position&#39;</span><span class="p">)</span></div><div class='line' id='LC537'>			<span class="p">};</span></div><div class='line' id='LC538'>			<span class="nx">element</span><span class="p">.</span><span class="nx">css</span><span class="p">({</span></div><div class='line' id='LC539'>				<span class="s1">&#39;display&#39;</span><span class="o">:</span> <span class="s1">&#39;normal&#39;</span><span class="p">,</span></div><div class='line' id='LC540'>				<span class="s1">&#39;left&#39;</span><span class="o">:</span> <span class="o">-</span><span class="mi">5000</span><span class="p">,</span></div><div class='line' id='LC541'>				<span class="s1">&#39;position&#39;</span><span class="o">:</span> <span class="s1">&#39;absolute&#39;</span></div><div class='line' id='LC542'>			<span class="p">});</span></div><div class='line' id='LC543'>			<span class="kd">var</span> <span class="nx">height</span> <span class="o">=</span> <span class="nx">element</span><span class="p">.</span><span class="nx">outerHeight</span><span class="p">();</span></div><div class='line' id='LC544'>			<span class="nx">element</span><span class="p">.</span><span class="nx">css</span><span class="p">(</span><span class="nx">prevCSS</span><span class="p">);</span></div><div class='line' id='LC545'>			<span class="k">return</span> <span class="nx">height</span><span class="p">;</span></div><div class='line' id='LC546'>		<span class="p">};</span></div><div class='line' id='LC547'><br/></div><div class='line' id='LC548'><br/></div><div class='line' id='LC549'>		<span class="c1">// Initialize the plugin</span></div><div class='line' id='LC550'>		<span class="nx">plugin</span><span class="p">.</span><span class="nx">init</span><span class="p">();</span></div><div class='line' id='LC551'>	<span class="p">};</span></div><div class='line' id='LC552'><br/></div><div class='line' id='LC553'>	<span class="cm">/*</span></div><div class='line' id='LC554'><span class="cm">	 * Handle input. Call public functions and initializers</span></div><div class='line' id='LC555'><span class="cm">	 */</span></div><div class='line' id='LC556'>	<span class="nx">$</span><span class="p">.</span><span class="nx">fn</span><span class="p">.</span><span class="nx">sliderTabs</span> <span class="o">=</span> <span class="kd">function</span><span class="p">(</span> <span class="nx">data</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC557'>		<span class="k">return</span> <span class="k">this</span><span class="p">.</span><span class="nx">each</span><span class="p">(</span><span class="kd">function</span><span class="p">(){</span></div><div class='line' id='LC558'>			<span class="kd">var</span> <span class="nx">_this</span> <span class="o">=</span> <span class="nx">$</span><span class="p">(</span><span class="k">this</span><span class="p">),</span></div><div class='line' id='LC559'>				<span class="nx">plugin</span> <span class="o">=</span> <span class="nx">_this</span><span class="p">.</span><span class="nx">data</span><span class="p">(</span><span class="s1">&#39;sliderTabs&#39;</span><span class="p">);</span></div><div class='line' id='LC560'><br/></div><div class='line' id='LC561'>			<span class="c1">// Method calling logic</span></div><div class='line' id='LC562'>		    <span class="k">if</span> <span class="p">(</span><span class="o">!</span><span class="nx">plugin</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC563'>		    	<span class="c1">// If no plugin, initialize it</span></div><div class='line' id='LC564'>				<span class="nx">plugin</span> <span class="o">=</span> <span class="k">new</span> <span class="nx">$</span><span class="p">.</span><span class="nx">sliderTabs</span><span class="p">(</span><span class="k">this</span><span class="p">,</span> <span class="nx">data</span><span class="p">);</span></div><div class='line' id='LC565'>				<span class="nx">_this</span><span class="p">.</span><span class="nx">data</span><span class="p">(</span><span class="s1">&#39;sliderTabs&#39;</span><span class="p">,</span> <span class="nx">plugin</span><span class="p">);</span></div><div class='line' id='LC566'>				<span class="k">return</span> <span class="nx">plugin</span><span class="p">;</span></div><div class='line' id='LC567'>			<span class="p">}</span></div><div class='line' id='LC568'>			<span class="k">if</span> <span class="p">(</span><span class="nx">plugin</span><span class="p">.</span><span class="nx">methods</span><span class="p">[</span><span class="nx">data</span><span class="p">]){</span></div><div class='line' id='LC569'>				<span class="c1">// If plugin exists, call a public method</span></div><div class='line' id='LC570'>				<span class="k">return</span> <span class="nx">plugin</span><span class="p">.</span><span class="nx">methods</span><span class="p">[</span> <span class="nx">data</span> <span class="p">].</span><span class="nx">apply</span><span class="p">(</span> <span class="k">this</span><span class="p">,</span> <span class="nb">Array</span><span class="p">.</span><span class="nx">prototype</span><span class="p">.</span><span class="nx">slice</span><span class="p">.</span><span class="nx">call</span><span class="p">(</span> <span class="nx">arguments</span><span class="p">,</span> <span class="mi">1</span> <span class="p">));</span></div><div class='line' id='LC571'>			<span class="p">}</span></div><div class='line' id='LC572'>		<span class="p">});</span></div><div class='line' id='LC573'>&nbsp;&nbsp;	<span class="p">};</span></div><div class='line' id='LC574'><span class="p">})(</span> <span class="nx">jQuery</span> <span class="p">);</span></div><div class='line' id='LC575'><br/></div><div class='line' id='LC576'><br/></div><div class='line' id='LC577'><br/></div><div class='line' id='LC578'><br/></div><div class='line' id='LC579'><br/></div><div class='line' id='LC580'><span class="cm">/*</span></div><div class='line' id='LC581'><span class="cm"> * Additional easing functions</span></div><div class='line' id='LC582'><span class="cm"> * Taken from jQuery UI source code</span></div><div class='line' id='LC583'><span class="cm"> * </span></div><div class='line' id='LC584'><span class="cm"> * https://github.com/jquery/jquery-ui</span></div><div class='line' id='LC585'><span class="cm"> */</span></div><div class='line' id='LC586'><br/></div><div class='line' id='LC587'><span class="nx">$</span><span class="p">.</span><span class="nx">extend</span><span class="p">(</span><span class="nx">$</span><span class="p">.</span><span class="nx">easing</span><span class="p">,</span></div><div class='line' id='LC588'><span class="p">{</span></div><div class='line' id='LC589'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">def</span><span class="o">:</span> <span class="s1">&#39;easeOutQuad&#39;</span><span class="p">,</span></div><div class='line' id='LC590'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">swing</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC591'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="c1">//alert($.easing.default);</span></div><div class='line' id='LC592'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">$</span><span class="p">.</span><span class="nx">easing</span><span class="p">[</span><span class="nx">$</span><span class="p">.</span><span class="nx">easing</span><span class="p">.</span><span class="nx">def</span><span class="p">](</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">);</span></div><div class='line' id='LC593'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC594'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeInQuad</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC595'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">c</span><span class="o">*</span><span class="p">(</span><span class="nx">t</span><span class="o">/=</span><span class="nx">d</span><span class="p">)</span><span class="o">*</span><span class="nx">t</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC596'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC597'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeOutQuad</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC598'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="o">-</span><span class="nx">c</span> <span class="o">*</span><span class="p">(</span><span class="nx">t</span><span class="o">/=</span><span class="nx">d</span><span class="p">)</span><span class="o">*</span><span class="p">(</span><span class="nx">t</span><span class="o">-</span><span class="mi">2</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC599'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC600'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeInOutQuad</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC601'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">((</span><span class="nx">t</span><span class="o">/=</span><span class="nx">d</span><span class="o">/</span><span class="mi">2</span><span class="p">)</span> <span class="o">&lt;</span> <span class="mi">1</span><span class="p">)</span> <span class="k">return</span> <span class="nx">c</span><span class="o">/</span><span class="mi">2</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC602'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="o">-</span><span class="nx">c</span><span class="o">/</span><span class="mi">2</span> <span class="o">*</span> <span class="p">((</span><span class="o">--</span><span class="nx">t</span><span class="p">)</span><span class="o">*</span><span class="p">(</span><span class="nx">t</span><span class="o">-</span><span class="mi">2</span><span class="p">)</span> <span class="o">-</span> <span class="mi">1</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC603'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC604'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeInCubic</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC605'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">c</span><span class="o">*</span><span class="p">(</span><span class="nx">t</span><span class="o">/=</span><span class="nx">d</span><span class="p">)</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC606'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC607'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeOutCubic</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC608'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">c</span><span class="o">*</span><span class="p">((</span><span class="nx">t</span><span class="o">=</span><span class="nx">t</span><span class="o">/</span><span class="nx">d</span><span class="o">-</span><span class="mi">1</span><span class="p">)</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span> <span class="o">+</span> <span class="mi">1</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC609'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC610'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeInOutCubic</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC611'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">((</span><span class="nx">t</span><span class="o">/=</span><span class="nx">d</span><span class="o">/</span><span class="mi">2</span><span class="p">)</span> <span class="o">&lt;</span> <span class="mi">1</span><span class="p">)</span> <span class="k">return</span> <span class="nx">c</span><span class="o">/</span><span class="mi">2</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC612'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">c</span><span class="o">/</span><span class="mi">2</span><span class="o">*</span><span class="p">((</span><span class="nx">t</span><span class="o">-=</span><span class="mi">2</span><span class="p">)</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span> <span class="o">+</span> <span class="mi">2</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC613'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC614'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeInQuart</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC615'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">c</span><span class="o">*</span><span class="p">(</span><span class="nx">t</span><span class="o">/=</span><span class="nx">d</span><span class="p">)</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC616'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC617'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeOutQuart</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC618'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="o">-</span><span class="nx">c</span> <span class="o">*</span> <span class="p">((</span><span class="nx">t</span><span class="o">=</span><span class="nx">t</span><span class="o">/</span><span class="nx">d</span><span class="o">-</span><span class="mi">1</span><span class="p">)</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span> <span class="o">-</span> <span class="mi">1</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC619'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC620'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeInOutQuart</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC621'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">((</span><span class="nx">t</span><span class="o">/=</span><span class="nx">d</span><span class="o">/</span><span class="mi">2</span><span class="p">)</span> <span class="o">&lt;</span> <span class="mi">1</span><span class="p">)</span> <span class="k">return</span> <span class="nx">c</span><span class="o">/</span><span class="mi">2</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC622'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="o">-</span><span class="nx">c</span><span class="o">/</span><span class="mi">2</span> <span class="o">*</span> <span class="p">((</span><span class="nx">t</span><span class="o">-=</span><span class="mi">2</span><span class="p">)</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span> <span class="o">-</span> <span class="mi">2</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC623'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC624'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeInQuint</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC625'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">c</span><span class="o">*</span><span class="p">(</span><span class="nx">t</span><span class="o">/=</span><span class="nx">d</span><span class="p">)</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC626'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC627'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeOutQuint</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC628'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">c</span><span class="o">*</span><span class="p">((</span><span class="nx">t</span><span class="o">=</span><span class="nx">t</span><span class="o">/</span><span class="nx">d</span><span class="o">-</span><span class="mi">1</span><span class="p">)</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span> <span class="o">+</span> <span class="mi">1</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC629'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC630'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeInOutQuint</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC631'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">((</span><span class="nx">t</span><span class="o">/=</span><span class="nx">d</span><span class="o">/</span><span class="mi">2</span><span class="p">)</span> <span class="o">&lt;</span> <span class="mi">1</span><span class="p">)</span> <span class="k">return</span> <span class="nx">c</span><span class="o">/</span><span class="mi">2</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC632'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">c</span><span class="o">/</span><span class="mi">2</span><span class="o">*</span><span class="p">((</span><span class="nx">t</span><span class="o">-=</span><span class="mi">2</span><span class="p">)</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span> <span class="o">+</span> <span class="mi">2</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC633'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC634'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeInSine</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC635'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="o">-</span><span class="nx">c</span> <span class="o">*</span> <span class="nb">Math</span><span class="p">.</span><span class="nx">cos</span><span class="p">(</span><span class="nx">t</span><span class="o">/</span><span class="nx">d</span> <span class="o">*</span> <span class="p">(</span><span class="nb">Math</span><span class="p">.</span><span class="nx">PI</span><span class="o">/</span><span class="mi">2</span><span class="p">))</span> <span class="o">+</span> <span class="nx">c</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC636'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC637'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeOutSine</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC638'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">c</span> <span class="o">*</span> <span class="nb">Math</span><span class="p">.</span><span class="nx">sin</span><span class="p">(</span><span class="nx">t</span><span class="o">/</span><span class="nx">d</span> <span class="o">*</span> <span class="p">(</span><span class="nb">Math</span><span class="p">.</span><span class="nx">PI</span><span class="o">/</span><span class="mi">2</span><span class="p">))</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC639'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC640'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeInOutSine</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC641'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="o">-</span><span class="nx">c</span><span class="o">/</span><span class="mi">2</span> <span class="o">*</span> <span class="p">(</span><span class="nb">Math</span><span class="p">.</span><span class="nx">cos</span><span class="p">(</span><span class="nb">Math</span><span class="p">.</span><span class="nx">PI</span><span class="o">*</span><span class="nx">t</span><span class="o">/</span><span class="nx">d</span><span class="p">)</span> <span class="o">-</span> <span class="mi">1</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC642'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC643'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeInExpo</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC644'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="p">(</span><span class="nx">t</span><span class="o">==</span><span class="mi">0</span><span class="p">)</span> <span class="o">?</span> <span class="nx">b</span> <span class="o">:</span> <span class="nx">c</span> <span class="o">*</span> <span class="nb">Math</span><span class="p">.</span><span class="nx">pow</span><span class="p">(</span><span class="mi">2</span><span class="p">,</span> <span class="mi">10</span> <span class="o">*</span> <span class="p">(</span><span class="nx">t</span><span class="o">/</span><span class="nx">d</span> <span class="o">-</span> <span class="mi">1</span><span class="p">))</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC645'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC646'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeOutExpo</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC647'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="p">(</span><span class="nx">t</span><span class="o">==</span><span class="nx">d</span><span class="p">)</span> <span class="o">?</span> <span class="nx">b</span><span class="o">+</span><span class="nx">c</span> <span class="o">:</span> <span class="nx">c</span> <span class="o">*</span> <span class="p">(</span><span class="o">-</span><span class="nb">Math</span><span class="p">.</span><span class="nx">pow</span><span class="p">(</span><span class="mi">2</span><span class="p">,</span> <span class="o">-</span><span class="mi">10</span> <span class="o">*</span> <span class="nx">t</span><span class="o">/</span><span class="nx">d</span><span class="p">)</span> <span class="o">+</span> <span class="mi">1</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC648'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC649'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeInOutExpo</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC650'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">(</span><span class="nx">t</span><span class="o">==</span><span class="mi">0</span><span class="p">)</span> <span class="k">return</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC651'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">(</span><span class="nx">t</span><span class="o">==</span><span class="nx">d</span><span class="p">)</span> <span class="k">return</span> <span class="nx">b</span><span class="o">+</span><span class="nx">c</span><span class="p">;</span></div><div class='line' id='LC652'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">((</span><span class="nx">t</span><span class="o">/=</span><span class="nx">d</span><span class="o">/</span><span class="mi">2</span><span class="p">)</span> <span class="o">&lt;</span> <span class="mi">1</span><span class="p">)</span> <span class="k">return</span> <span class="nx">c</span><span class="o">/</span><span class="mi">2</span> <span class="o">*</span> <span class="nb">Math</span><span class="p">.</span><span class="nx">pow</span><span class="p">(</span><span class="mi">2</span><span class="p">,</span> <span class="mi">10</span> <span class="o">*</span> <span class="p">(</span><span class="nx">t</span> <span class="o">-</span> <span class="mi">1</span><span class="p">))</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC653'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">c</span><span class="o">/</span><span class="mi">2</span> <span class="o">*</span> <span class="p">(</span><span class="o">-</span><span class="nb">Math</span><span class="p">.</span><span class="nx">pow</span><span class="p">(</span><span class="mi">2</span><span class="p">,</span> <span class="o">-</span><span class="mi">10</span> <span class="o">*</span> <span class="o">--</span><span class="nx">t</span><span class="p">)</span> <span class="o">+</span> <span class="mi">2</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC654'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC655'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeInCirc</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC656'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="o">-</span><span class="nx">c</span> <span class="o">*</span> <span class="p">(</span><span class="nb">Math</span><span class="p">.</span><span class="nx">sqrt</span><span class="p">(</span><span class="mi">1</span> <span class="o">-</span> <span class="p">(</span><span class="nx">t</span><span class="o">/=</span><span class="nx">d</span><span class="p">)</span><span class="o">*</span><span class="nx">t</span><span class="p">)</span> <span class="o">-</span> <span class="mi">1</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC657'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC658'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeOutCirc</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC659'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">c</span> <span class="o">*</span> <span class="nb">Math</span><span class="p">.</span><span class="nx">sqrt</span><span class="p">(</span><span class="mi">1</span> <span class="o">-</span> <span class="p">(</span><span class="nx">t</span><span class="o">=</span><span class="nx">t</span><span class="o">/</span><span class="nx">d</span><span class="o">-</span><span class="mi">1</span><span class="p">)</span><span class="o">*</span><span class="nx">t</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC660'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC661'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeInOutCirc</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC662'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">((</span><span class="nx">t</span><span class="o">/=</span><span class="nx">d</span><span class="o">/</span><span class="mi">2</span><span class="p">)</span> <span class="o">&lt;</span> <span class="mi">1</span><span class="p">)</span> <span class="k">return</span> <span class="o">-</span><span class="nx">c</span><span class="o">/</span><span class="mi">2</span> <span class="o">*</span> <span class="p">(</span><span class="nb">Math</span><span class="p">.</span><span class="nx">sqrt</span><span class="p">(</span><span class="mi">1</span> <span class="o">-</span> <span class="nx">t</span><span class="o">*</span><span class="nx">t</span><span class="p">)</span> <span class="o">-</span> <span class="mi">1</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC663'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">c</span><span class="o">/</span><span class="mi">2</span> <span class="o">*</span> <span class="p">(</span><span class="nb">Math</span><span class="p">.</span><span class="nx">sqrt</span><span class="p">(</span><span class="mi">1</span> <span class="o">-</span> <span class="p">(</span><span class="nx">t</span><span class="o">-=</span><span class="mi">2</span><span class="p">)</span><span class="o">*</span><span class="nx">t</span><span class="p">)</span> <span class="o">+</span> <span class="mi">1</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC664'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC665'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeInElastic</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC666'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="kd">var</span> <span class="nx">s</span><span class="o">=</span><span class="mf">1.70158</span><span class="p">;</span><span class="kd">var</span> <span class="nx">p</span><span class="o">=</span><span class="mi">0</span><span class="p">;</span><span class="kd">var</span> <span class="nx">a</span><span class="o">=</span><span class="nx">c</span><span class="p">;</span></div><div class='line' id='LC667'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">(</span><span class="nx">t</span><span class="o">==</span><span class="mi">0</span><span class="p">)</span> <span class="k">return</span> <span class="nx">b</span><span class="p">;</span>  <span class="k">if</span> <span class="p">((</span><span class="nx">t</span><span class="o">/=</span><span class="nx">d</span><span class="p">)</span><span class="o">==</span><span class="mi">1</span><span class="p">)</span> <span class="k">return</span> <span class="nx">b</span><span class="o">+</span><span class="nx">c</span><span class="p">;</span>  <span class="k">if</span> <span class="p">(</span><span class="o">!</span><span class="nx">p</span><span class="p">)</span> <span class="nx">p</span><span class="o">=</span><span class="nx">d</span><span class="o">*</span><span class="p">.</span><span class="mi">3</span><span class="p">;</span></div><div class='line' id='LC668'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">(</span><span class="nx">a</span> <span class="o">&lt;</span> <span class="nb">Math</span><span class="p">.</span><span class="nx">abs</span><span class="p">(</span><span class="nx">c</span><span class="p">))</span> <span class="p">{</span> <span class="nx">a</span><span class="o">=</span><span class="nx">c</span><span class="p">;</span> <span class="kd">var</span> <span class="nx">s</span><span class="o">=</span><span class="nx">p</span><span class="o">/</span><span class="mi">4</span><span class="p">;</span> <span class="p">}</span></div><div class='line' id='LC669'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">else</span> <span class="kd">var</span> <span class="nx">s</span> <span class="o">=</span> <span class="nx">p</span><span class="o">/</span><span class="p">(</span><span class="mi">2</span><span class="o">*</span><span class="nb">Math</span><span class="p">.</span><span class="nx">PI</span><span class="p">)</span> <span class="o">*</span> <span class="nb">Math</span><span class="p">.</span><span class="nx">asin</span> <span class="p">(</span><span class="nx">c</span><span class="o">/</span><span class="nx">a</span><span class="p">);</span></div><div class='line' id='LC670'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="o">-</span><span class="p">(</span><span class="nx">a</span><span class="o">*</span><span class="nb">Math</span><span class="p">.</span><span class="nx">pow</span><span class="p">(</span><span class="mi">2</span><span class="p">,</span><span class="mi">10</span><span class="o">*</span><span class="p">(</span><span class="nx">t</span><span class="o">-=</span><span class="mi">1</span><span class="p">))</span> <span class="o">*</span> <span class="nb">Math</span><span class="p">.</span><span class="nx">sin</span><span class="p">(</span> <span class="p">(</span><span class="nx">t</span><span class="o">*</span><span class="nx">d</span><span class="o">-</span><span class="nx">s</span><span class="p">)</span><span class="o">*</span><span class="p">(</span><span class="mi">2</span><span class="o">*</span><span class="nb">Math</span><span class="p">.</span><span class="nx">PI</span><span class="p">)</span><span class="o">/</span><span class="nx">p</span> <span class="p">))</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC671'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC672'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeOutElastic</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC673'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="kd">var</span> <span class="nx">s</span><span class="o">=</span><span class="mf">1.70158</span><span class="p">;</span><span class="kd">var</span> <span class="nx">p</span><span class="o">=</span><span class="mi">0</span><span class="p">;</span><span class="kd">var</span> <span class="nx">a</span><span class="o">=</span><span class="nx">c</span><span class="p">;</span></div><div class='line' id='LC674'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">(</span><span class="nx">t</span><span class="o">==</span><span class="mi">0</span><span class="p">)</span> <span class="k">return</span> <span class="nx">b</span><span class="p">;</span>  <span class="k">if</span> <span class="p">((</span><span class="nx">t</span><span class="o">/=</span><span class="nx">d</span><span class="p">)</span><span class="o">==</span><span class="mi">1</span><span class="p">)</span> <span class="k">return</span> <span class="nx">b</span><span class="o">+</span><span class="nx">c</span><span class="p">;</span>  <span class="k">if</span> <span class="p">(</span><span class="o">!</span><span class="nx">p</span><span class="p">)</span> <span class="nx">p</span><span class="o">=</span><span class="nx">d</span><span class="o">*</span><span class="p">.</span><span class="mi">3</span><span class="p">;</span></div><div class='line' id='LC675'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">(</span><span class="nx">a</span> <span class="o">&lt;</span> <span class="nb">Math</span><span class="p">.</span><span class="nx">abs</span><span class="p">(</span><span class="nx">c</span><span class="p">))</span> <span class="p">{</span> <span class="nx">a</span><span class="o">=</span><span class="nx">c</span><span class="p">;</span> <span class="kd">var</span> <span class="nx">s</span><span class="o">=</span><span class="nx">p</span><span class="o">/</span><span class="mi">4</span><span class="p">;</span> <span class="p">}</span></div><div class='line' id='LC676'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">else</span> <span class="kd">var</span> <span class="nx">s</span> <span class="o">=</span> <span class="nx">p</span><span class="o">/</span><span class="p">(</span><span class="mi">2</span><span class="o">*</span><span class="nb">Math</span><span class="p">.</span><span class="nx">PI</span><span class="p">)</span> <span class="o">*</span> <span class="nb">Math</span><span class="p">.</span><span class="nx">asin</span> <span class="p">(</span><span class="nx">c</span><span class="o">/</span><span class="nx">a</span><span class="p">);</span></div><div class='line' id='LC677'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">a</span><span class="o">*</span><span class="nb">Math</span><span class="p">.</span><span class="nx">pow</span><span class="p">(</span><span class="mi">2</span><span class="p">,</span><span class="o">-</span><span class="mi">10</span><span class="o">*</span><span class="nx">t</span><span class="p">)</span> <span class="o">*</span> <span class="nb">Math</span><span class="p">.</span><span class="nx">sin</span><span class="p">(</span> <span class="p">(</span><span class="nx">t</span><span class="o">*</span><span class="nx">d</span><span class="o">-</span><span class="nx">s</span><span class="p">)</span><span class="o">*</span><span class="p">(</span><span class="mi">2</span><span class="o">*</span><span class="nb">Math</span><span class="p">.</span><span class="nx">PI</span><span class="p">)</span><span class="o">/</span><span class="nx">p</span> <span class="p">)</span> <span class="o">+</span> <span class="nx">c</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC678'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC679'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeInOutElastic</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC680'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="kd">var</span> <span class="nx">s</span><span class="o">=</span><span class="mf">1.70158</span><span class="p">;</span><span class="kd">var</span> <span class="nx">p</span><span class="o">=</span><span class="mi">0</span><span class="p">;</span><span class="kd">var</span> <span class="nx">a</span><span class="o">=</span><span class="nx">c</span><span class="p">;</span></div><div class='line' id='LC681'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">(</span><span class="nx">t</span><span class="o">==</span><span class="mi">0</span><span class="p">)</span> <span class="k">return</span> <span class="nx">b</span><span class="p">;</span>  <span class="k">if</span> <span class="p">((</span><span class="nx">t</span><span class="o">/=</span><span class="nx">d</span><span class="o">/</span><span class="mi">2</span><span class="p">)</span><span class="o">==</span><span class="mi">2</span><span class="p">)</span> <span class="k">return</span> <span class="nx">b</span><span class="o">+</span><span class="nx">c</span><span class="p">;</span>  <span class="k">if</span> <span class="p">(</span><span class="o">!</span><span class="nx">p</span><span class="p">)</span> <span class="nx">p</span><span class="o">=</span><span class="nx">d</span><span class="o">*</span><span class="p">(.</span><span class="mi">3</span><span class="o">*</span><span class="mf">1.5</span><span class="p">);</span></div><div class='line' id='LC682'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">(</span><span class="nx">a</span> <span class="o">&lt;</span> <span class="nb">Math</span><span class="p">.</span><span class="nx">abs</span><span class="p">(</span><span class="nx">c</span><span class="p">))</span> <span class="p">{</span> <span class="nx">a</span><span class="o">=</span><span class="nx">c</span><span class="p">;</span> <span class="kd">var</span> <span class="nx">s</span><span class="o">=</span><span class="nx">p</span><span class="o">/</span><span class="mi">4</span><span class="p">;</span> <span class="p">}</span></div><div class='line' id='LC683'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">else</span> <span class="kd">var</span> <span class="nx">s</span> <span class="o">=</span> <span class="nx">p</span><span class="o">/</span><span class="p">(</span><span class="mi">2</span><span class="o">*</span><span class="nb">Math</span><span class="p">.</span><span class="nx">PI</span><span class="p">)</span> <span class="o">*</span> <span class="nb">Math</span><span class="p">.</span><span class="nx">asin</span> <span class="p">(</span><span class="nx">c</span><span class="o">/</span><span class="nx">a</span><span class="p">);</span></div><div class='line' id='LC684'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">(</span><span class="nx">t</span> <span class="o">&lt;</span> <span class="mi">1</span><span class="p">)</span> <span class="k">return</span> <span class="o">-</span><span class="p">.</span><span class="mi">5</span><span class="o">*</span><span class="p">(</span><span class="nx">a</span><span class="o">*</span><span class="nb">Math</span><span class="p">.</span><span class="nx">pow</span><span class="p">(</span><span class="mi">2</span><span class="p">,</span><span class="mi">10</span><span class="o">*</span><span class="p">(</span><span class="nx">t</span><span class="o">-=</span><span class="mi">1</span><span class="p">))</span> <span class="o">*</span> <span class="nb">Math</span><span class="p">.</span><span class="nx">sin</span><span class="p">(</span> <span class="p">(</span><span class="nx">t</span><span class="o">*</span><span class="nx">d</span><span class="o">-</span><span class="nx">s</span><span class="p">)</span><span class="o">*</span><span class="p">(</span><span class="mi">2</span><span class="o">*</span><span class="nb">Math</span><span class="p">.</span><span class="nx">PI</span><span class="p">)</span><span class="o">/</span><span class="nx">p</span> <span class="p">))</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC685'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">a</span><span class="o">*</span><span class="nb">Math</span><span class="p">.</span><span class="nx">pow</span><span class="p">(</span><span class="mi">2</span><span class="p">,</span><span class="o">-</span><span class="mi">10</span><span class="o">*</span><span class="p">(</span><span class="nx">t</span><span class="o">-=</span><span class="mi">1</span><span class="p">))</span> <span class="o">*</span> <span class="nb">Math</span><span class="p">.</span><span class="nx">sin</span><span class="p">(</span> <span class="p">(</span><span class="nx">t</span><span class="o">*</span><span class="nx">d</span><span class="o">-</span><span class="nx">s</span><span class="p">)</span><span class="o">*</span><span class="p">(</span><span class="mi">2</span><span class="o">*</span><span class="nb">Math</span><span class="p">.</span><span class="nx">PI</span><span class="p">)</span><span class="o">/</span><span class="nx">p</span> <span class="p">)</span><span class="o">*</span><span class="p">.</span><span class="mi">5</span> <span class="o">+</span> <span class="nx">c</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC686'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC687'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeInBack</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">,</span> <span class="nx">s</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC688'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">(</span><span class="nx">s</span> <span class="o">==</span> <span class="kc">undefined</span><span class="p">)</span> <span class="nx">s</span> <span class="o">=</span> <span class="mf">1.70158</span><span class="p">;</span></div><div class='line' id='LC689'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">c</span><span class="o">*</span><span class="p">(</span><span class="nx">t</span><span class="o">/=</span><span class="nx">d</span><span class="p">)</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="p">((</span><span class="nx">s</span><span class="o">+</span><span class="mi">1</span><span class="p">)</span><span class="o">*</span><span class="nx">t</span> <span class="o">-</span> <span class="nx">s</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC690'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC691'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeOutBack</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">,</span> <span class="nx">s</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC692'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">(</span><span class="nx">s</span> <span class="o">==</span> <span class="kc">undefined</span><span class="p">)</span> <span class="nx">s</span> <span class="o">=</span> <span class="mf">1.70158</span><span class="p">;</span></div><div class='line' id='LC693'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">c</span><span class="o">*</span><span class="p">((</span><span class="nx">t</span><span class="o">=</span><span class="nx">t</span><span class="o">/</span><span class="nx">d</span><span class="o">-</span><span class="mi">1</span><span class="p">)</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="p">((</span><span class="nx">s</span><span class="o">+</span><span class="mi">1</span><span class="p">)</span><span class="o">*</span><span class="nx">t</span> <span class="o">+</span> <span class="nx">s</span><span class="p">)</span> <span class="o">+</span> <span class="mi">1</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC694'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC695'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeInOutBack</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">,</span> <span class="nx">s</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC696'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">(</span><span class="nx">s</span> <span class="o">==</span> <span class="kc">undefined</span><span class="p">)</span> <span class="nx">s</span> <span class="o">=</span> <span class="mf">1.70158</span><span class="p">;</span></div><div class='line' id='LC697'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">((</span><span class="nx">t</span><span class="o">/=</span><span class="nx">d</span><span class="o">/</span><span class="mi">2</span><span class="p">)</span> <span class="o">&lt;</span> <span class="mi">1</span><span class="p">)</span> <span class="k">return</span> <span class="nx">c</span><span class="o">/</span><span class="mi">2</span><span class="o">*</span><span class="p">(</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="p">(((</span><span class="nx">s</span><span class="o">*=</span><span class="p">(</span><span class="mf">1.525</span><span class="p">))</span><span class="o">+</span><span class="mi">1</span><span class="p">)</span><span class="o">*</span><span class="nx">t</span> <span class="o">-</span> <span class="nx">s</span><span class="p">))</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC698'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">c</span><span class="o">/</span><span class="mi">2</span><span class="o">*</span><span class="p">((</span><span class="nx">t</span><span class="o">-=</span><span class="mi">2</span><span class="p">)</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="p">(((</span><span class="nx">s</span><span class="o">*=</span><span class="p">(</span><span class="mf">1.525</span><span class="p">))</span><span class="o">+</span><span class="mi">1</span><span class="p">)</span><span class="o">*</span><span class="nx">t</span> <span class="o">+</span> <span class="nx">s</span><span class="p">)</span> <span class="o">+</span> <span class="mi">2</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC699'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC700'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeInBounce</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC701'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">c</span> <span class="o">-</span> <span class="nx">$</span><span class="p">.</span><span class="nx">easing</span><span class="p">.</span><span class="nx">easeOutBounce</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">d</span><span class="o">-</span><span class="nx">t</span><span class="p">,</span> <span class="mi">0</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC702'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC703'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeOutBounce</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC704'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">((</span><span class="nx">t</span><span class="o">/=</span><span class="nx">d</span><span class="p">)</span> <span class="o">&lt;</span> <span class="p">(</span><span class="mi">1</span><span class="o">/</span><span class="mf">2.75</span><span class="p">))</span> <span class="p">{</span></div><div class='line' id='LC705'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">c</span><span class="o">*</span><span class="p">(</span><span class="mf">7.5625</span><span class="o">*</span><span class="nx">t</span><span class="o">*</span><span class="nx">t</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC706'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">}</span> <span class="k">else</span> <span class="k">if</span> <span class="p">(</span><span class="nx">t</span> <span class="o">&lt;</span> <span class="p">(</span><span class="mi">2</span><span class="o">/</span><span class="mf">2.75</span><span class="p">))</span> <span class="p">{</span></div><div class='line' id='LC707'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">c</span><span class="o">*</span><span class="p">(</span><span class="mf">7.5625</span><span class="o">*</span><span class="p">(</span><span class="nx">t</span><span class="o">-=</span><span class="p">(</span><span class="mf">1.5</span><span class="o">/</span><span class="mf">2.75</span><span class="p">))</span><span class="o">*</span><span class="nx">t</span> <span class="o">+</span> <span class="p">.</span><span class="mi">75</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC708'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">}</span> <span class="k">else</span> <span class="k">if</span> <span class="p">(</span><span class="nx">t</span> <span class="o">&lt;</span> <span class="p">(</span><span class="mf">2.5</span><span class="o">/</span><span class="mf">2.75</span><span class="p">))</span> <span class="p">{</span></div><div class='line' id='LC709'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">c</span><span class="o">*</span><span class="p">(</span><span class="mf">7.5625</span><span class="o">*</span><span class="p">(</span><span class="nx">t</span><span class="o">-=</span><span class="p">(</span><span class="mf">2.25</span><span class="o">/</span><span class="mf">2.75</span><span class="p">))</span><span class="o">*</span><span class="nx">t</span> <span class="o">+</span> <span class="p">.</span><span class="mi">9375</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC710'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">}</span> <span class="k">else</span> <span class="p">{</span></div><div class='line' id='LC711'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">c</span><span class="o">*</span><span class="p">(</span><span class="mf">7.5625</span><span class="o">*</span><span class="p">(</span><span class="nx">t</span><span class="o">-=</span><span class="p">(</span><span class="mf">2.625</span><span class="o">/</span><span class="mf">2.75</span><span class="p">))</span><span class="o">*</span><span class="nx">t</span> <span class="o">+</span> <span class="p">.</span><span class="mi">984375</span><span class="p">)</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC712'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">}</span></div><div class='line' id='LC713'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC714'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">easeInOutBounce</span><span class="o">:</span> <span class="kd">function</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="p">,</span> <span class="nx">b</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC715'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">(</span><span class="nx">t</span> <span class="o">&lt;</span> <span class="nx">d</span><span class="o">/</span><span class="mi">2</span><span class="p">)</span> <span class="k">return</span> <span class="nx">$</span><span class="p">.</span><span class="nx">easing</span><span class="p">.</span><span class="nx">easeInBounce</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="o">*</span><span class="mi">2</span><span class="p">,</span> <span class="mi">0</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="o">*</span> <span class="p">.</span><span class="mi">5</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC716'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">$</span><span class="p">.</span><span class="nx">easing</span><span class="p">.</span><span class="nx">easeOutBounce</span> <span class="p">(</span><span class="nx">x</span><span class="p">,</span> <span class="nx">t</span><span class="o">*</span><span class="mi">2</span><span class="o">-</span><span class="nx">d</span><span class="p">,</span> <span class="mi">0</span><span class="p">,</span> <span class="nx">c</span><span class="p">,</span> <span class="nx">d</span><span class="p">)</span> <span class="o">*</span> <span class="p">.</span><span class="mi">5</span> <span class="o">+</span> <span class="nx">c</span><span class="o">*</span><span class="p">.</span><span class="mi">5</span> <span class="o">+</span> <span class="nx">b</span><span class="p">;</span></div><div class='line' id='LC717'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">}</span></div><div class='line' id='LC718'><span class="p">});</span></div><div class='line' id='LC719'><br/></div><div class='line' id='LC720'><br/></div><div class='line' id='LC721'><br/></div><div class='line' id='LC722'><br/></div><div class='line' id='LC723'><br/></div><div class='line' id='LC724'><span class="cm">/*</span></div><div class='line' id='LC725'><span class="cm"> * The following is the jQuery Mousewheel plugin. Full credit goes to </span></div><div class='line' id='LC726'><span class="cm"> * Brandon Aaron. (https://github.com/brandonaaron/jquery-mousewheel)</span></div><div class='line' id='LC727'><span class="cm"> * /</span></div><div class='line' id='LC728'><br/></div><div class='line' id='LC729'><br/></div><div class='line' id='LC730'><span class="cm">/*! Copyright (c) 2011 Brandon Aaron (http://brandonaaron.net)</span></div><div class='line' id='LC731'><span class="cm"> * Licensed under the MIT License (LICENSE.txt).</span></div><div class='line' id='LC732'><span class="cm"> *</span></div><div class='line' id='LC733'><span class="cm"> * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.</span></div><div class='line' id='LC734'><span class="cm"> * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.</span></div><div class='line' id='LC735'><span class="cm"> * Thanks to: Seamus Leahy for adding deltaX and deltaY</span></div><div class='line' id='LC736'><span class="cm"> *</span></div><div class='line' id='LC737'><span class="cm"> * Version: 3.0.6</span></div><div class='line' id='LC738'><span class="cm"> * </span></div><div class='line' id='LC739'><span class="cm"> * Requires: 1.2.2+</span></div><div class='line' id='LC740'><span class="cm"> */</span></div><div class='line' id='LC741'><br/></div><div class='line' id='LC742'><span class="p">(</span><span class="kd">function</span><span class="p">(</span><span class="nx">$</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC743'><br/></div><div class='line' id='LC744'><span class="kd">var</span> <span class="nx">types</span> <span class="o">=</span> <span class="p">[</span><span class="s1">&#39;DOMMouseScroll&#39;</span><span class="p">,</span> <span class="s1">&#39;mousewheel&#39;</span><span class="p">];</span></div><div class='line' id='LC745'><br/></div><div class='line' id='LC746'><span class="k">if</span> <span class="p">(</span><span class="nx">$</span><span class="p">.</span><span class="nx">event</span><span class="p">.</span><span class="nx">fixHooks</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC747'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">for</span> <span class="p">(</span> <span class="kd">var</span> <span class="nx">i</span><span class="o">=</span><span class="nx">types</span><span class="p">.</span><span class="nx">length</span><span class="p">;</span> <span class="nx">i</span><span class="p">;</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC748'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">$</span><span class="p">.</span><span class="nx">event</span><span class="p">.</span><span class="nx">fixHooks</span><span class="p">[</span> <span class="nx">types</span><span class="p">[</span><span class="o">--</span><span class="nx">i</span><span class="p">]</span> <span class="p">]</span> <span class="o">=</span> <span class="nx">$</span><span class="p">.</span><span class="nx">event</span><span class="p">.</span><span class="nx">mouseHooks</span><span class="p">;</span></div><div class='line' id='LC749'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">}</span></div><div class='line' id='LC750'><span class="p">}</span></div><div class='line' id='LC751'><br/></div><div class='line' id='LC752'><span class="nx">$</span><span class="p">.</span><span class="nx">event</span><span class="p">.</span><span class="nx">special</span><span class="p">.</span><span class="nx">mousewheel</span> <span class="o">=</span> <span class="p">{</span></div><div class='line' id='LC753'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">setup</span><span class="o">:</span> <span class="kd">function</span><span class="p">()</span> <span class="p">{</span></div><div class='line' id='LC754'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">(</span> <span class="k">this</span><span class="p">.</span><span class="nx">addEventListener</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC755'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">for</span> <span class="p">(</span> <span class="kd">var</span> <span class="nx">i</span><span class="o">=</span><span class="nx">types</span><span class="p">.</span><span class="nx">length</span><span class="p">;</span> <span class="nx">i</span><span class="p">;</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC756'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">this</span><span class="p">.</span><span class="nx">addEventListener</span><span class="p">(</span> <span class="nx">types</span><span class="p">[</span><span class="o">--</span><span class="nx">i</span><span class="p">],</span> <span class="nx">handler</span><span class="p">,</span> <span class="kc">false</span> <span class="p">);</span></div><div class='line' id='LC757'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">}</span></div><div class='line' id='LC758'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">}</span> <span class="k">else</span> <span class="p">{</span></div><div class='line' id='LC759'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">this</span><span class="p">.</span><span class="nx">onmousewheel</span> <span class="o">=</span> <span class="nx">handler</span><span class="p">;</span></div><div class='line' id='LC760'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">}</span></div><div class='line' id='LC761'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC762'>&nbsp;&nbsp;&nbsp;&nbsp;</div><div class='line' id='LC763'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">teardown</span><span class="o">:</span> <span class="kd">function</span><span class="p">()</span> <span class="p">{</span></div><div class='line' id='LC764'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">(</span> <span class="k">this</span><span class="p">.</span><span class="nx">removeEventListener</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC765'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">for</span> <span class="p">(</span> <span class="kd">var</span> <span class="nx">i</span><span class="o">=</span><span class="nx">types</span><span class="p">.</span><span class="nx">length</span><span class="p">;</span> <span class="nx">i</span><span class="p">;</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC766'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">this</span><span class="p">.</span><span class="nx">removeEventListener</span><span class="p">(</span> <span class="nx">types</span><span class="p">[</span><span class="o">--</span><span class="nx">i</span><span class="p">],</span> <span class="nx">handler</span><span class="p">,</span> <span class="kc">false</span> <span class="p">);</span></div><div class='line' id='LC767'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">}</span></div><div class='line' id='LC768'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">}</span> <span class="k">else</span> <span class="p">{</span></div><div class='line' id='LC769'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">this</span><span class="p">.</span><span class="nx">onmousewheel</span> <span class="o">=</span> <span class="kc">null</span><span class="p">;</span></div><div class='line' id='LC770'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">}</span></div><div class='line' id='LC771'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">}</span></div><div class='line' id='LC772'><span class="p">};</span></div><div class='line' id='LC773'><br/></div><div class='line' id='LC774'><span class="nx">$</span><span class="p">.</span><span class="nx">fn</span><span class="p">.</span><span class="nx">extend</span><span class="p">({</span></div><div class='line' id='LC775'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">mousewheel</span><span class="o">:</span> <span class="kd">function</span><span class="p">(</span><span class="nx">fn</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC776'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="nx">fn</span> <span class="o">?</span> <span class="k">this</span><span class="p">.</span><span class="nx">bind</span><span class="p">(</span><span class="s2">&quot;mousewheel&quot;</span><span class="p">,</span> <span class="nx">fn</span><span class="p">)</span> <span class="o">:</span> <span class="k">this</span><span class="p">.</span><span class="nx">trigger</span><span class="p">(</span><span class="s2">&quot;mousewheel&quot;</span><span class="p">);</span></div><div class='line' id='LC777'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">},</span></div><div class='line' id='LC778'>&nbsp;&nbsp;&nbsp;&nbsp;</div><div class='line' id='LC779'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">unmousewheel</span><span class="o">:</span> <span class="kd">function</span><span class="p">(</span><span class="nx">fn</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC780'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="k">this</span><span class="p">.</span><span class="nx">unbind</span><span class="p">(</span><span class="s2">&quot;mousewheel&quot;</span><span class="p">,</span> <span class="nx">fn</span><span class="p">);</span></div><div class='line' id='LC781'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">}</span></div><div class='line' id='LC782'><span class="p">});</span></div><div class='line' id='LC783'><br/></div><div class='line' id='LC784'><br/></div><div class='line' id='LC785'><span class="kd">function</span> <span class="nx">handler</span><span class="p">(</span><span class="nx">event</span><span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC786'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="kd">var</span> <span class="nx">orgEvent</span> <span class="o">=</span> <span class="nx">event</span> <span class="o">||</span> <span class="nb">window</span><span class="p">.</span><span class="nx">event</span><span class="p">,</span> <span class="nx">args</span> <span class="o">=</span> <span class="p">[].</span><span class="nx">slice</span><span class="p">.</span><span class="nx">call</span><span class="p">(</span> <span class="nx">arguments</span><span class="p">,</span> <span class="mi">1</span> <span class="p">),</span> <span class="nx">delta</span> <span class="o">=</span> <span class="mi">0</span><span class="p">,</span> <span class="nx">returnValue</span> <span class="o">=</span> <span class="kc">true</span><span class="p">,</span> <span class="nx">deltaX</span> <span class="o">=</span> <span class="mi">0</span><span class="p">,</span> <span class="nx">deltaY</span> <span class="o">=</span> <span class="mi">0</span><span class="p">;</span></div><div class='line' id='LC787'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">event</span> <span class="o">=</span> <span class="nx">$</span><span class="p">.</span><span class="nx">event</span><span class="p">.</span><span class="nx">fix</span><span class="p">(</span><span class="nx">orgEvent</span><span class="p">);</span></div><div class='line' id='LC788'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">event</span><span class="p">.</span><span class="nx">type</span> <span class="o">=</span> <span class="s2">&quot;mousewheel&quot;</span><span class="p">;</span></div><div class='line' id='LC789'>&nbsp;&nbsp;&nbsp;&nbsp;</div><div class='line' id='LC790'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="c1">// Old school scrollwheel delta</span></div><div class='line' id='LC791'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">(</span> <span class="nx">orgEvent</span><span class="p">.</span><span class="nx">wheelDelta</span> <span class="p">)</span> <span class="p">{</span> <span class="nx">delta</span> <span class="o">=</span> <span class="nx">orgEvent</span><span class="p">.</span><span class="nx">wheelDelta</span><span class="o">/</span><span class="mi">120</span><span class="p">;</span> <span class="p">}</span></div><div class='line' id='LC792'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">(</span> <span class="nx">orgEvent</span><span class="p">.</span><span class="nx">detail</span>     <span class="p">)</span> <span class="p">{</span> <span class="nx">delta</span> <span class="o">=</span> <span class="o">-</span><span class="nx">orgEvent</span><span class="p">.</span><span class="nx">detail</span><span class="o">/</span><span class="mi">3</span><span class="p">;</span> <span class="p">}</span></div><div class='line' id='LC793'>&nbsp;&nbsp;&nbsp;&nbsp;</div><div class='line' id='LC794'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="c1">// New school multidimensional scroll (touchpads) deltas</span></div><div class='line' id='LC795'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">deltaY</span> <span class="o">=</span> <span class="nx">delta</span><span class="p">;</span></div><div class='line' id='LC796'>&nbsp;&nbsp;&nbsp;&nbsp;</div><div class='line' id='LC797'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="c1">// Gecko</span></div><div class='line' id='LC798'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">(</span> <span class="nx">orgEvent</span><span class="p">.</span><span class="nx">axis</span> <span class="o">!==</span> <span class="kc">undefined</span> <span class="o">&amp;&amp;</span> <span class="nx">orgEvent</span><span class="p">.</span><span class="nx">axis</span> <span class="o">===</span> <span class="nx">orgEvent</span><span class="p">.</span><span class="nx">HORIZONTAL_AXIS</span> <span class="p">)</span> <span class="p">{</span></div><div class='line' id='LC799'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">deltaY</span> <span class="o">=</span> <span class="mi">0</span><span class="p">;</span></div><div class='line' id='LC800'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">deltaX</span> <span class="o">=</span> <span class="o">-</span><span class="mi">1</span><span class="o">*</span><span class="nx">delta</span><span class="p">;</span></div><div class='line' id='LC801'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="p">}</span></div><div class='line' id='LC802'>&nbsp;&nbsp;&nbsp;&nbsp;</div><div class='line' id='LC803'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="c1">// Webkit</span></div><div class='line' id='LC804'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">(</span> <span class="nx">orgEvent</span><span class="p">.</span><span class="nx">wheelDeltaY</span> <span class="o">!==</span> <span class="kc">undefined</span> <span class="p">)</span> <span class="p">{</span> <span class="nx">deltaY</span> <span class="o">=</span> <span class="nx">orgEvent</span><span class="p">.</span><span class="nx">wheelDeltaY</span><span class="o">/</span><span class="mi">120</span><span class="p">;</span> <span class="p">}</span></div><div class='line' id='LC805'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">if</span> <span class="p">(</span> <span class="nx">orgEvent</span><span class="p">.</span><span class="nx">wheelDeltaX</span> <span class="o">!==</span> <span class="kc">undefined</span> <span class="p">)</span> <span class="p">{</span> <span class="nx">deltaX</span> <span class="o">=</span> <span class="o">-</span><span class="mi">1</span><span class="o">*</span><span class="nx">orgEvent</span><span class="p">.</span><span class="nx">wheelDeltaX</span><span class="o">/</span><span class="mi">120</span><span class="p">;</span> <span class="p">}</span></div><div class='line' id='LC806'>&nbsp;&nbsp;&nbsp;&nbsp;</div><div class='line' id='LC807'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="c1">// Add event and delta to the front of the arguments</span></div><div class='line' id='LC808'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="nx">args</span><span class="p">.</span><span class="nx">unshift</span><span class="p">(</span><span class="nx">event</span><span class="p">,</span> <span class="nx">delta</span><span class="p">,</span> <span class="nx">deltaX</span><span class="p">,</span> <span class="nx">deltaY</span><span class="p">);</span></div><div class='line' id='LC809'>&nbsp;&nbsp;&nbsp;&nbsp;</div><div class='line' id='LC810'>&nbsp;&nbsp;&nbsp;&nbsp;<span class="k">return</span> <span class="p">(</span><span class="nx">$</span><span class="p">.</span><span class="nx">event</span><span class="p">.</span><span class="nx">dispatch</span> <span class="o">||</span> <span class="nx">$</span><span class="p">.</span><span class="nx">event</span><span class="p">.</span><span class="nx">handle</span><span class="p">).</span><span class="nx">apply</span><span class="p">(</span><span class="k">this</span><span class="p">,</span> <span class="nx">args</span><span class="p">);</span></div><div class='line' id='LC811'><span class="p">}</span></div><div class='line' id='LC812'><br/></div><div class='line' id='LC813'><span class="p">})(</span><span class="nx">jQuery</span><span class="p">);</span></div><div class='line' id='LC814'><br/></div><div class='line' id='LC815'><br/></div><div class='line' id='LC816'><br/></div><div class='line' id='LC817'><span class="cm">/*</span></div><div class='line' id='LC818'><span class="cm"> * The following is the jQuery Resize plugin. Full credit goes to </span></div><div class='line' id='LC819'><span class="cm"> * &quot;Cowboy&quot; Ben Alman. (https://github.com/cowboy/jquery-resize)</span></div><div class='line' id='LC820'><span class="cm"> * /</span></div><div class='line' id='LC821'><br/></div><div class='line' id='LC822'><span class="cm">/*</span></div><div class='line' id='LC823'><span class="cm"> * jQuery resize event - v1.1 - 3/14/2010</span></div><div class='line' id='LC824'><span class="cm"> * http://benalman.com/projects/jquery-resize-plugin/</span></div><div class='line' id='LC825'><span class="cm"> * </span></div><div class='line' id='LC826'><span class="cm"> * Copyright (c) 2010 &quot;Cowboy&quot; Ben Alman</span></div><div class='line' id='LC827'><span class="cm"> * Dual licensed under the MIT and GPL licenses.</span></div><div class='line' id='LC828'><span class="cm"> * http://benalman.com/about/license/</span></div><div class='line' id='LC829'><span class="cm"> */</span></div><div class='line' id='LC830'><span class="p">(</span><span class="kd">function</span><span class="p">(</span><span class="nx">$</span><span class="p">,</span><span class="nx">h</span><span class="p">,</span><span class="nx">c</span><span class="p">){</span><span class="kd">var</span> <span class="nx">a</span><span class="o">=</span><span class="nx">$</span><span class="p">([]),</span><span class="nx">e</span><span class="o">=</span><span class="nx">$</span><span class="p">.</span><span class="nx">resize</span><span class="o">=</span><span class="nx">$</span><span class="p">.</span><span class="nx">extend</span><span class="p">(</span><span class="nx">$</span><span class="p">.</span><span class="nx">resize</span><span class="p">,{}),</span><span class="nx">i</span><span class="p">,</span><span class="nx">k</span><span class="o">=</span><span class="s2">&quot;setTimeout&quot;</span><span class="p">,</span><span class="nx">j</span><span class="o">=</span><span class="s2">&quot;resize&quot;</span><span class="p">,</span><span class="nx">d</span><span class="o">=</span><span class="nx">j</span><span class="o">+</span><span class="s2">&quot;-special-event&quot;</span><span class="p">,</span><span class="nx">b</span><span class="o">=</span><span class="s2">&quot;delay&quot;</span><span class="p">,</span><span class="nx">f</span><span class="o">=</span><span class="s2">&quot;throttleWindow&quot;</span><span class="p">;</span><span class="nx">e</span><span class="p">[</span><span class="nx">b</span><span class="p">]</span><span class="o">=</span><span class="mi">250</span><span class="p">;</span><span class="nx">e</span><span class="p">[</span><span class="nx">f</span><span class="p">]</span><span class="o">=</span><span class="kc">true</span><span class="p">;</span><span class="nx">$</span><span class="p">.</span><span class="nx">event</span><span class="p">.</span><span class="nx">special</span><span class="p">[</span><span class="nx">j</span><span class="p">]</span><span class="o">=</span><span class="p">{</span><span class="nx">setup</span><span class="o">:</span><span class="kd">function</span><span class="p">(){</span><span class="k">if</span><span class="p">(</span><span class="o">!</span><span class="nx">e</span><span class="p">[</span><span class="nx">f</span><span class="p">]</span><span class="o">&amp;&amp;</span><span class="k">this</span><span class="p">[</span><span class="nx">k</span><span class="p">]){</span><span class="k">return</span> <span class="kc">false</span><span class="p">}</span><span class="kd">var</span> <span class="nx">l</span><span class="o">=</span><span class="nx">$</span><span class="p">(</span><span class="k">this</span><span class="p">);</span><span class="nx">a</span><span class="o">=</span><span class="nx">a</span><span class="p">.</span><span class="nx">add</span><span class="p">(</span><span class="nx">l</span><span class="p">);</span><span class="nx">$</span><span class="p">.</span><span class="nx">data</span><span class="p">(</span><span class="k">this</span><span class="p">,</span><span class="nx">d</span><span class="p">,{</span><span class="nx">w</span><span class="o">:</span><span class="nx">l</span><span class="p">.</span><span class="nx">width</span><span class="p">(),</span><span class="nx">h</span><span class="o">:</span><span class="nx">l</span><span class="p">.</span><span class="nx">height</span><span class="p">()});</span><span class="k">if</span><span class="p">(</span><span class="nx">a</span><span class="p">.</span><span class="nx">length</span><span class="o">===</span><span class="mi">1</span><span class="p">){</span><span class="nx">g</span><span class="p">()}},</span><span class="nx">teardown</span><span class="o">:</span><span class="kd">function</span><span class="p">(){</span><span class="k">if</span><span class="p">(</span><span class="o">!</span><span class="nx">e</span><span class="p">[</span><span class="nx">f</span><span class="p">]</span><span class="o">&amp;&amp;</span><span class="k">this</span><span class="p">[</span><span class="nx">k</span><span class="p">]){</span><span class="k">return</span> <span class="kc">false</span><span class="p">}</span><span class="kd">var</span> <span class="nx">l</span><span class="o">=</span><span class="nx">$</span><span class="p">(</span><span class="k">this</span><span class="p">);</span><span class="nx">a</span><span class="o">=</span><span class="nx">a</span><span class="p">.</span><span class="nx">not</span><span class="p">(</span><span class="nx">l</span><span class="p">);</span><span class="nx">l</span><span class="p">.</span><span class="nx">removeData</span><span class="p">(</span><span class="nx">d</span><span class="p">);</span><span class="k">if</span><span class="p">(</span><span class="o">!</span><span class="nx">a</span><span class="p">.</span><span class="nx">length</span><span class="p">){</span><span class="nx">clearTimeout</span><span class="p">(</span><span class="nx">i</span><span class="p">)}},</span><span class="nx">add</span><span class="o">:</span><span class="kd">function</span><span class="p">(</span><span class="nx">l</span><span class="p">){</span><span class="k">if</span><span class="p">(</span><span class="o">!</span><span class="nx">e</span><span class="p">[</span><span class="nx">f</span><span class="p">]</span><span class="o">&amp;&amp;</span><span class="k">this</span><span class="p">[</span><span class="nx">k</span><span class="p">]){</span><span class="k">return</span> <span class="kc">false</span><span class="p">}</span><span class="kd">var</span> <span class="nx">n</span><span class="p">;</span><span class="kd">function</span> <span class="nx">m</span><span class="p">(</span><span class="nx">s</span><span class="p">,</span><span class="nx">o</span><span class="p">,</span><span class="nx">p</span><span class="p">){</span><span class="kd">var</span> <span class="nx">q</span><span class="o">=</span><span class="nx">$</span><span class="p">(</span><span class="k">this</span><span class="p">),</span><span class="nx">r</span><span class="o">=</span><span class="nx">$</span><span class="p">.</span><span class="nx">data</span><span class="p">(</span><span class="k">this</span><span class="p">,</span><span class="nx">d</span><span class="p">);</span><span class="nx">r</span><span class="p">.</span><span class="nx">w</span><span class="o">=</span><span class="nx">o</span><span class="o">!==</span><span class="nx">c</span><span class="o">?</span><span class="nx">o</span><span class="o">:</span><span class="nx">q</span><span class="p">.</span><span class="nx">width</span><span class="p">();</span><span class="nx">r</span><span class="p">.</span><span class="nx">h</span><span class="o">=</span><span class="nx">p</span><span class="o">!==</span><span class="nx">c</span><span class="o">?</span><span class="nx">p</span><span class="o">:</span><span class="nx">q</span><span class="p">.</span><span class="nx">height</span><span class="p">();</span><span class="nx">n</span><span class="p">.</span><span class="nx">apply</span><span class="p">(</span><span class="k">this</span><span class="p">,</span><span class="nx">arguments</span><span class="p">)}</span><span class="k">if</span><span class="p">(</span><span class="nx">$</span><span class="p">.</span><span class="nx">isFunction</span><span class="p">(</span><span class="nx">l</span><span class="p">)){</span><span class="nx">n</span><span class="o">=</span><span class="nx">l</span><span class="p">;</span><span class="k">return</span> <span class="nx">m</span><span class="p">}</span><span class="k">else</span><span class="p">{</span><span class="nx">n</span><span class="o">=</span><span class="nx">l</span><span class="p">.</span><span class="nx">handler</span><span class="p">;</span><span class="nx">l</span><span class="p">.</span><span class="nx">handler</span><span class="o">=</span><span class="nx">m</span><span class="p">}}};</span><span class="kd">function</span> <span class="nx">g</span><span class="p">(){</span><span class="nx">i</span><span class="o">=</span><span class="nx">h</span><span class="p">[</span><span class="nx">k</span><span class="p">](</span><span class="kd">function</span><span class="p">(){</span><span class="nx">a</span><span class="p">.</span><span class="nx">each</span><span class="p">(</span><span class="kd">function</span><span class="p">(){</span><span class="kd">var</span> <span class="nx">n</span><span class="o">=</span><span class="nx">$</span><span class="p">(</span><span class="k">this</span><span class="p">),</span><span class="nx">m</span><span class="o">=</span><span class="nx">n</span><span class="p">.</span><span class="nx">width</span><span class="p">(),</span><span class="nx">l</span><span class="o">=</span><span class="nx">n</span><span class="p">.</span><span class="nx">height</span><span class="p">(),</span><span class="nx">o</span><span class="o">=</span><span class="nx">$</span><span class="p">.</span><span class="nx">data</span><span class="p">(</span><span class="k">this</span><span class="p">,</span><span class="nx">d</span><span class="p">);</span><span class="k">if</span><span class="p">(</span><span class="nx">m</span><span class="o">!==</span><span class="nx">o</span><span class="p">.</span><span class="nx">w</span><span class="o">||</span><span class="nx">l</span><span class="o">!==</span><span class="nx">o</span><span class="p">.</span><span class="nx">h</span><span class="p">){</span><span class="nx">n</span><span class="p">.</span><span class="nx">trigger</span><span class="p">(</span><span class="nx">j</span><span class="p">,[</span><span class="nx">o</span><span class="p">.</span><span class="nx">w</span><span class="o">=</span><span class="nx">m</span><span class="p">,</span><span class="nx">o</span><span class="p">.</span><span class="nx">h</span><span class="o">=</span><span class="nx">l</span><span class="p">])}});</span><span class="nx">g</span><span class="p">()},</span><span class="nx">e</span><span class="p">[</span><span class="nx">b</span><span class="p">])}})(</span><span class="nx">jQuery</span><span class="p">,</span><span class="k">this</span><span class="p">);</span></div></pre></div></td>
          </tr>
        </table>
  </div>

  </div>
</div>

<a href="#jump-to-line" rel="facebox[.linejump]" data-hotkey="l" class="js-jump-to-line" style="display:none">Jump to Line</a>
<div id="jump-to-line" style="display:none">
  <form accept-charset="UTF-8" class="js-jump-to-line-form">
    <input class="linejump-input js-jump-to-line-field" type="text" placeholder="Jump to line&hellip;" autofocus>
    <button type="submit" class="button">Go</button>
  </form>
</div>

        </div>

      </div><!-- /.repo-container -->
      <div class="modal-backdrop"></div>
    </div><!-- /.container -->
  </div><!-- /.site -->


    </div><!-- /.wrapper -->

      <div class="container">
  <div class="site-footer">
    <ul class="site-footer-links right">
      <li><a href="https://status.github.com/">Status</a></li>
      <li><a href="http://developer.github.com">API</a></li>
      <li><a href="http://training.github.com">Training</a></li>
      <li><a href="http://shop.github.com">Shop</a></li>
      <li><a href="/blog">Blog</a></li>
      <li><a href="/about">About</a></li>

    </ul>

    <a href="/">
      <span class="mega-octicon octicon-mark-github" title="GitHub"></span>
    </a>

    <ul class="site-footer-links">
      <li>&copy; 2014 <span title="0.02891s from github-fe139-cp1-prd.iad.github.net">GitHub</span>, Inc.</li>
        <li><a href="/site/terms">Terms</a></li>
        <li><a href="/site/privacy">Privacy</a></li>
        <li><a href="/security">Security</a></li>
        <li><a href="/contact">Contact</a></li>
    </ul>
  </div><!-- /.site-footer -->
</div><!-- /.container -->


    <div class="fullscreen-overlay js-fullscreen-overlay" id="fullscreen_overlay">
  <div class="fullscreen-container js-fullscreen-container">
    <div class="textarea-wrap">
      <textarea name="fullscreen-contents" id="fullscreen-contents" class="js-fullscreen-contents" placeholder="" data-suggester="fullscreen_suggester"></textarea>
    </div>
  </div>
  <div class="fullscreen-sidebar">
    <a href="#" class="exit-fullscreen js-exit-fullscreen tooltipped tooltipped-w" aria-label="Exit Zen Mode">
      <span class="mega-octicon octicon-screen-normal"></span>
    </a>
    <a href="#" class="theme-switcher js-theme-switcher tooltipped tooltipped-w"
      aria-label="Switch themes">
      <span class="octicon octicon-color-mode"></span>
    </a>
  </div>
</div>



    <div id="ajax-error-message" class="flash flash-error">
      <span class="octicon octicon-alert"></span>
      <a href="#" class="octicon octicon-remove-close close js-ajax-error-dismiss"></a>
      Something went wrong with that request. Please try again.
    </div>

  </body>
</html>

