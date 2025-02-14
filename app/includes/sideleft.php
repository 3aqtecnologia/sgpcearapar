<ul class="nav am-sideleft-tab">
  <?php
  if ($_SESSION['LEVEL'] < 2) { ?>
    <li class="nav-item" style="width: 120px; !important;">
      <a href="#mainMenu" class="nav-link <?= !isset($_GET['page']) || isset($_GET['nav']) && $_GET['nav'] == 1 ? 'active' : ''; ?>">
        <i class="icon fa fa-th tx-24"></i>
      </a>
    </li>
    <li class="nav-item" style="width: 120px !important;">
      <a href="#settingMenu" class="nav-link <?= isset($_GET['settings']) && $_GET['settings'] == 1 ? 'active' : ''; ?>">
        <i class="icon fa fa-sliders tx-24"></i>
        <!-- <i class="icon ion-ios-gear-outline tx-24"></i> -->
      </a>
    </li>
  <?php } else { ?>
    <li class="nav-item" style="width: 100%; !important;">
      <a href="#mainMenu" class="nav-link active">
        <i class="icon fa fa-th tx-24"></i>
      </a>
    </li>

  <?php } ?>
</ul>

<div class="tab-content">
  <?php
  $documents = match ($_GET['page']) {
    'ordinances' => 'active  show-sub',
    'ordinance' => 'active  show-sub',
    'legislations' => 'active  show-sub',
    'legislation' => 'active  show-sub',
    'managerOrdinances' => 'active  ',
    default => ''
  };

  ?>
  <div id="mainMenu" class="tab-pane <?= !isset($_GET['page']) || $_GET['page'] == 'inicio' || isset($_GET['nav']) ? 'active' : ''; ?>  ">
    <ul class="nav am-sideleft-menu">
      <li class="nav-item">
        <a href="?page=inicio&nav=1" class="nav-link <?= !isset($_GET['page']) || $_GET['page'] == 'inicio' ? 'active' : ''; ?> ">
          <i class="icon ion-ios-home-outline"></i>
          <span>HOME</span>
        </a>
      </li>
      <!-- nav-item -->
      <li class="nav-item">
        <a href="" class="nav-link with-sub <?= $documents ?> ">
          <!-- <i class="icon ion-document-text"></i> -->
          <!-- <ion-icon class="icon tx-18 mr-2" name="documents-outline"></ion-icon> -->
          <i class="icon tx-18 mr-2 mdil mdil-file-multiple"></i>
          <span>Documentos</span>
        </a>
        <ul class="nav-sub">
          <li class="nav-item">
            <a href="?page=ordinances&nav=1" class="nav-link <?= $_GET['page'] == 'ordinances' ||  $_GET['page'] == 'ordinance' ?  $documents : '' ?>  ">Portarias</a>
          </li>
          <li class="nav-item">
            <a href="?page=legislations&nav=1" class="nav-link <?= $_GET['page'] == 'legislations' ||  $_GET['page'] == 'legislation' ?  $documents : '' ?>  ">Legislação</a>
          </li>
        </ul>
      </li>
      <!-- nav-item -->
      <!-- <li class="nav-item">
        <a href="" class="nav-link with-sub">
          <i class="icon ion-ios-gear-outline"></i>
          <span>Forms</span>
        </a>
        <ul class="nav-sub">
          <li class="nav-item">
            <a href="form-elements.html" class="nav-link">Form Elements</a>
          </li>
          <li class="nav-item">
            <a href="form-layouts.html" class="nav-link">Form Layouts</a>
          </li>
        </ul>
      </li> -->
      <!-- nav-item -->

      <!-- <li class="nav-item">
        <a href="widgets.html" class="nav-link">
          <i class="icon ion-ios-briefcase-outline"></i>
          <span>Widgets</span>
        </a>
      </li> -->
      <!-- nav-item -->
    </ul>
  </div>
  <!-- #mainMenu -->
  <div id="emailMenu" class="tab-pane">
    <div class="pd-x-20 pd-y-10">
      <a href="" class="btn btn-orange btn-block btn-compose">Compose Email</a>
    </div>
    <ul class="nav am-sideleft-menu">
      <li class="nav-item">
        <a href="page-inbox.html" class="nav-link">
          <i class="icon ion-ios-filing-outline"></i>
          <span>Inbox</span>
        </a>
      </li>
      <!-- nav-item -->
      <li class="nav-item">
        <a href="page-inbox.html" class="nav-link">
          <i class="icon ion-ios-filing-outline"></i>
          <span>Drafts</span>
        </a>
      </li>
      <!-- nav-item -->
      <li class="nav-item">
        <a href="page-inbox.html" class="nav-link">
          <i class="icon ion-ios-paperplane-outline"></i>
          <span>Sent</span>
        </a>
      </li>
      <!-- nav-item -->
      <li class="nav-item">
        <a href="page-inbox.html" class="nav-link">
          <i class="icon ion-ios-trash-outline"></i>
          <span>Trash</span>
        </a>
      </li>
      <!-- nav-item -->
      <li class="nav-item">
        <a href="page-inbox.html" class="nav-link">
          <i class="icon ion-ios-filing-outline"></i>
          <span>Spam</span>
        </a>
      </li>
      <!-- nav-item -->
    </ul>

    <label class="pd-x-20 tx-uppercase tx-11 mg-t-10 tx-orange mg-b-0 tx-medium">My Folder</label>
    <ul class="nav am-sideleft-menu">
      <li class="nav-item">
        <a href="page-inbox.html" class="nav-link">
          <i class="icon ion-ios-folder-outline"></i>
          <span>Updates</span>
        </a>
      </li>
      <!-- nav-item -->
      <li class="nav-item">
        <a href="page-inbox.html" class="nav-link">
          <i class="icon ion-ios-folder-outline"></i>
          <span>Social</span>
        </a>
      </li>
      <!-- nav-item -->
      <li class="nav-item">
        <a href="page-inbox.html" class="nav-link">
          <i class="icon ion-ios-folder-outline"></i>
          <span>Promotions</span>
        </a>
      </li>
      <!-- nav-item -->
    </ul>
  </div>
  <!-- #emailMenu -->
  <div id="chatMenu" class="tab-pane">
    <div class="chat-search-bar">
      <input type="search" class="form-control wd-150" placeholder="Search chat..." />
      <button class="btn btn-secondary">
        <i class="fa fa-search"></i>
      </button>
    </div>
    <!-- chat-search-bar -->

    <label class="pd-x-15 tx-uppercase tx-11 mg-t-20 tx-orange mg-b-10 tx-medium">Recent Chat History</label>
    <div class="list-group list-group-chat">
      <a href="#" class="list-group-item">
        <div class="d-flex align-items-center">
          <img src="../img/img6.jpg" class="wd-32 rounded-circle" alt="" />
          <div class="mg-l-10">
            <h6>Russell M. Evans</h6>
            <span>Tuesday, 10:33am</span>
          </div>
        </div>
        <!-- d-flex -->
        <p>
          Nor again is there anyone who loves or pursues or desires to
          obtain pain of itself, because it is pain...
        </p>
      </a><!-- list-group-item -->
      <a href="#" class="list-group-item">
        <div class="d-flex align-items-center">
          <img src="../img/img7.jpg" class="wd-32 rounded-circle" alt="" />
          <div class="mg-l-10">
            <h6>James F. Sears</h6>
            <span>Monday, 4:21pm</span>
          </div>
        </div>
        <!-- d-flex -->
        <p>
          But who has any right to find fault with a man who chooses to
          enjoy a pleasure that has...
        </p>
      </a><!-- list-group-item -->
      <a href="#" class="list-group-item">
        <div class="d-flex align-items-center">
          <img src="../img/img8.jpg" class="wd-32 rounded-circle" alt="" />
          <div class="mg-l-10">
            <h6>Sharon R. Rowe</h6>
            <span>Sunday, 7:45pm</span>
          </div>
        </div>
        <!-- d-flex -->
        <p>
          But I must explain to you how all this mistaken idea of
          denouncing pleasure and praising...
        </p>
      </a><!-- list-group-item -->
      <a href="#" class="list-group-item">
        <div class="d-flex align-items-center">
          <img src="../img/img9.jpg" class="wd-32 rounded-circle" alt="" />
          <div class="mg-l-10">
            <h6>Ruby M. Martin</h6>
            <span>Sunday, 7:45pm</span>
          </div>
        </div>
        <!-- d-flex -->
        <p>
          But I must explain to you how all this mistaken idea of
          denouncing pleasure and praising...
        </p>
      </a><!-- list-group-item -->
      <a href="#" class="list-group-item">
        <div class="d-flex align-items-center">
          <img src="../img/img10.jpg" class="wd-32 rounded-circle" alt="" />
          <div class="mg-l-10">
            <h6>Joslyn C. Mayo</h6>
            <span>Sunday, 7:45pm</span>
          </div>
        </div>
        <!-- d-flex -->
        <p>
          But I must explain to you how all this mistaken idea of
          denouncing pleasure and praising...
        </p>
      </a><!-- list-group-item -->
    </div>
    <!-- list-group -->
    <span class="d-block pd-15 tx-12">Loading messages...</span>
  </div>
  <!-- #chatMenu -->
  <div id="settingMenu" class="tab-pane <?= $_GET['page'] == 'users' || $_GET['page'] == 'managerOrdinances' || $_GET['page'] == 'user' ? 'active' : ''; ?>">
    <ul class="nav am-sideleft-menu">
      <li class="nav-item">
        <a href="?page=users&settings=1" class="nav-link <?= $_GET['page'] == 'users' || $_GET['page'] == 'user' ? 'active' : ''; ?>">
          <i class="icon ion-ios-people-outline"></i>
          <span>Usuários</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="?page=managerOrdinances&settings=1" class="nav-link <?= $documents ?>">
          <i class="icon tx-17 mr-2 mdil mdil-tag"></i>
          <span>Tipos de Portária</span>
        </a>
      </li>
      <!-- nav-item -->
      <!-- <li class="nav-item">
        <a href="" class="nav-link with-sub">
          <i class="icon ion-ios-people-outline"></i>
          <span>Usuário</span>
        </a>
        <ul class="nav-sub">
          <li class="nav-item">
            <a href="" class="nav-link">Portarias</a>
          </li>
          <li class="nav-item">
            <a href="" class="nav-link">Relatórios</a>
          </li>
        </ul>
      </li> -->
      <!-- nav-item -->
      <!-- <li class="nav-item">
        <a href="" class="nav-link with-sub">
          <i class="icon ion-ios-gear-outline"></i>
          <span>Forms</span>
        </a>
        <ul class="nav-sub">
          <li class="nav-item">
            <a href="form-elements.html" class="nav-link">Form Elements</a>
          </li>
          <li class="nav-item">
            <a href="form-layouts.html" class="nav-link">Form Layouts</a>
          </li>
        </ul>
      </li> -->
      <!-- nav-item -->

      <!-- <li class="nav-item">
        <a href="widgets.html" class="nav-link">
          <i class="icon ion-ios-briefcase-outline"></i>
          <span>Widgets</span>
        </a>
      </li> -->
      <!-- nav-item -->
    </ul>

    <!-- <div class="pd-x-15"> -->
    <!-- <label class="tx-uppercase tx-11 mg-t-10 tx-orange mg-b-15 tx-medium">Quick Settings</label> -->
    <!-- <div class="bd pd-15">
        <h6 class="tx-13 tx-normal tx-gray-800">Daily Newsletter</h6>
        <p class="tx-12">
          Get notified when someone else is trying to access your account.
        </p>
        <div class="toggle toggle-light warning"></div>
      </div> -->
    <!-- bd -->
    <!-- </div> -->
  </div>
  <!-- #settingMenu -->
</div>
<!-- tab-content -->
