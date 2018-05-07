<h1 class="login-heading">Hackthorn</h1>
<form class="login-form" action="<?= htmlspecialchars($url) ?>" method="post" enctype="multipart/form-data" novalidate>

    <ul class="login__menu">
        <li class="login__menu-item login__menu-item--2">
            <input placeholder="username" title="Please enter the username" type="text" name="username" value="<?= htmlspecialchars($username) ?>">
        </li>
        <li class="login__menu-item login__menu-item--2">
            <input placeholder="email" title="Please enter the email" type="text" name="email" value="<?= htmlspecialchars($email) ?>">
        </li>
        <li class="login__menu-item login__menu-item--2">
            <input placeholder="password" title="Please enter the password" type="password" name="password">
        </li>
        <li class="login__menu-item login__menu-item--2">
            <input placeholder="confirm password" title="Please re-enter the password" type="password" name="verify">
        </li>
    </ul>

    <div class="upload-btn-wrapper">
        <label class="file__upload-control" for="file__input-id">
            <input id="file__input-id" type="file" name="profile">
            <p class="file__upload-name">Add Image</p>
        </label>
    </div>
    <input type="hidden" name="registerCSRF" value="<?= htmlspecialchars($registerCSRF) ?>">

    <input  class="login-button" type="image" src="<?= htmlspecialchars($button)?>" alt="login Button">
</form>