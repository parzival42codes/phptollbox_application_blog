<div class="flex-container"
     style="flex-direction: row;">
    <div style="flex: 3;">
        <div class="card-container card-container--shadow">
            <div class="card-container-header">
                {$title}
            </div>
            <div class="card-container-content">
                {$content}
            </div>
        </div>
        <div class="card-container card-container--shadow">
            <div class="card-container-content">
                <h2>{insert/language class="ApplicationBlogView" path="/header/comment"
                    language-de_DE="Kommentare"
                    language-en_US="Comments"}</h2>
            </div>
        </div>
        <div style="overflow-y: auto; height: 25em;">
            {$comments}
        </div>
    </div>
    <div style="flex: 1;">
        <div class="card-container card-container--shadow">
            <div class="card-container-header">
                {insert/language class="ApplicationBlogView" path="/category"
                language-de_DE="Kategorie"
                language-en_US="Category"}
            </div>
            <div class="card-container-content">
                {$category}
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

