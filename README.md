# wp-bootstrap-nav-walker

A WordPress nav walker for Bootstrap v4.

@see <a href="https://developer.wordpress.org/reference/functions/wp_nav_menu/">wp_nav_menu();</a>

```php
<nav class="navbar navbar-expand-lg navbar-light bg-light">

    <a class="navbar-brand" href="#">Navbar</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-navbar" aria-controls="main-navbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <?php
    $menu_args = [
        'container'       => 'div',
        'container_id'    => 'main-navbar',
        'container_class' => 'collapse navbar-collapse',
        'menu'            => 'primary',
        'theme_location'  => 'primary',
        'menu_class'      => 'nav navbar-nav navbar-right mr-auto',
        'walker'          => new DBDB\BS_Nav_Walker()
    ];
    ?>

    <?php wp_nav_menu( $menu_args ); ?>

</nav>    
```