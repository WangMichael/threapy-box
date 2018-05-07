<h1 class="login-heading">Hackthorn</h1>
<form class="login-form" action="<?= htmlspecialchars($url) ?>" method="post" novalidate>
    <ul class="login__menu">
        <li class="login__menu-item">
            <input placeholder="username" title="Please enter the username" type="text" name="username" value="<?= htmlspecialchars($username) ?>">
        </li>
        <li class="login__menu-item">
            <input placeholder="password" title="Please enter the password" type="password" name="password">
        </li>
    </ul>
    <input type="hidden" name="loginCSRF" value="<?= htmlspecialchars($loginCSRF) ?>">

    <input class="login-button" type="image" src="<?= htmlspecialchars($button)?>" alt="login Button">
</form>
<h2 class="login-register">New to the Hackthorn? <strong><a href="/register">Sign UP</a></strong></h2>