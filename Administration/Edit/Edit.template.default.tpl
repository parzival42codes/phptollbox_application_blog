{$registerHeader}

<div class="flex-container"
     style="flex-direction: row;">
    <div style="flex: 3;">
        <div class="card-container card-container--shadow">
            <div class="card-container-header">
                {$crudTitle}
            </div>
            <div class="card-container-content">
                {$crudContent}
            </div>
        </div>
    </div>
    <div style="flex: 1;">
        <div class="card-container card-container--shadow">
            <div class="card-container-header">
                {insert/language class="ApplicationBlogAdministrationEdit" path="/status"
                language-de_DE="Status"
                language-en_US="Status"}
            </div>
            <div class="card-container-content">
                {$status}
            </div>
        </div>
        <div class="card-container card-container--shadow">
            <div class="card-container-header">
                {insert/language class="ApplicationBlogAdministrationEdit" path="/category"
                language-de_DE="Kategorie"
                language-en_US="Category"}
            </div>
            <div class="card-container-content">
                {$crudCategory}
            </div>
        </div>
        <div class="card-container card-container--shadow">
            <div class="card-container-header">
                {insert/language class="ApplicationBlogAdministrationEdit" path="/datetime"
                language-de_DE="Erstellt"
                language-en_US="Created"}
            </div>
            <div class="card-container-content">
                {$datetime}
            </div>
        </div>
        <div class="card-container card-container--shadow">
            <div class="card-container-header">
                {insert/language class="ApplicationBlogAdministrationEdit" path="/count/view"
                language-de_DE="Aufrufe"
                language-en_US="Views"}
            </div>
            <div class="card-container-content">
                {$viewCount}
            </div>
        </div>
        <div class="card-container card-container--shadow">
            <div class="card-container-header">
                {insert/language class="ApplicationBlogAdministrationEdit" path="/count/commenats"
                language-de_DE="Kommentare"
                language-en_US="Comments"}
            </div>
            <div class="card-container-content">
                {$commentCount}
            </div>
        </div>
    </div>
</div>

{$registerFooter}
