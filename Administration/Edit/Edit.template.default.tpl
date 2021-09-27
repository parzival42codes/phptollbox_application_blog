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
                {insert/language class="ApplicationBlogView" path="/datetime"
                language-de_DE="Erstellt"
                language-en_US="Created"}
            </div>
            <div class="card-container-content">
                {$datetime}
            </div>
        </div>
        <div class="card-container card-container--shadow">
            <div class="card-container-header">
                {insert/language class="ApplicationBlogView" path="/count/view"
                language-de_DE="Aufrufe"
                language-en_US="Views"}
            </div>
            <div class="card-container-content">
                {$viewCount}
            </div>
        </div>
        <div class="card-container card-container--shadow">
            <div class="card-container-header">
                {insert/language class="ApplicationBlogView" path="/count/commenats"
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
