{if isset($_error)}
    <div id="mensaje" class="error">
        <span class="closebtn" onclick="this.parentElement.style.display = 'none'">&times;</span>
        <h3>Error</h3>
        <p>{$_error}</p>
    </div>
{/if}
{if isset($_mensaje)}
    <div id="mensaje"  class="mensaje"> 
        <span class="closebtn" onclick="this.parentElement.style.display = 'none'">&times;</span>
        <h3>Mensaje</h3>
        <p>{$_mensaje}</p>
    </div>
{/if}