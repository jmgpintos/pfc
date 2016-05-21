{if isset($_error)}
    <div class="container red " id="error">
        <span class="closebtn" onclick="this.parentElement.style.display = 'none'">X</span>
        <h3>Error</h3>
        <p>{$_error}</p>
    </div>
{/if}
{if Session::getError()}
    <div class="container red " id="error">
        <span class="closebtn" onclick="this.parentElement.style.display = 'none'">X</span>
        <h3>Error</h3>
        <p>{Session::getError()}</p>
    </div>
{/if}
{if isset($_mensaje)}
    <div class="container bottombar pale-green  border-green " id="mensaje"> 
        <span class="closebtn" onclick="this.parentElement.style.display = 'none'">X</span>
        <h3>Mensaje</h3>
        <p>{$_mensaje}</p>
    </div>
{/if}
{if Session::getMensaje()}
    <div class="container bottombar pale-green  border-green " id="mensaje"> 
        <span class="closebtn" onclick="this.parentElement.style.display = 'none'">X</span>
        <h3>Mensaje</h3>
        <p>{Session::getMensaje()}</p>
    </div>
{/if}