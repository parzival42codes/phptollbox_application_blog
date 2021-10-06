<div class="flex-container">
    <div class="flex-container-item">
        {create/pagination ident="blog"}
    </div>
    <div class="flex-container-item">
        {create/filter ident="blog"}
    </div>
</div>
<div class="card-container card-container--shadow">
    <div class="card-container-header">
        {insert/language class="ApplicationBlogAdministration" path="/default/entries"
        language-de_DE="Blog Einträge"
        language-en_US="Blog Entries"}
    </div>
    <div class="card-container-content">
        <div class="card-container-content">
            <CMS function="_table">
                {
                "_config": {
                "cssClass": "template-table-standard template-table-standard-small template-table-standard-monospace",
                "table": "Table",
                "source": "Table"
                },
                "crudId": {
                "titleHeader": "{insert/language class="ApplicationBlogAdministration" path="/table/crudId"
                language-de_DE="ID"
                language-en_US="ID"}"
                },
                "status": {
                "titleHeader": "{insert/language class="ApplicationBlogAdministration" path="/table/crudStatus"
                language-de_DE="Status"
                language-en_US="Status"}"
                },
                "crudTitle": {
                "titleHeader": "{insert/language class="ApplicationBlogAdministration" path="/table/crudTitle"
                language-de_DE="Titel"
                language-en_US="Title"}"
                },
                "crudText": {
                "titleHeader": "{insert/language class="ApplicationBlogAdministration" path="/table/crudText"
                language-de_DE="Text"
                language-en_US="Text"}"
                },
                "categoryCategory": {
                "titleHeader": "{insert/language class="ApplicationBlogAdministration" path="/table/categoryCategory"
                language-de_DE="Kategorie"
                language-en_US="Category"}"
                },
                "crudViewCount": {
                "titleHeader": "{insert/language class="ApplicationBlogAdministration" path="/table/crudViewCount"
                language-de_DE="Anzahl Views"
                language-en_US="Count View"}"
                },
                "commentCount": {
                "titleHeader": "{insert/language class="ApplicationBlogAdministration" path="/table/commentCount"
                language-de_DE="Anzahl Kommentare"
                language-en_US="Count Comment"}"
                },
                "dates": {
                "titleHeader": "{insert/language class="ApplicationBlogAdministration" path="/table/dates"
                language-de_DE="Datum"
                language-en_US="Dates"}"
                },
                "action": {
                "titleHeader": "{insert/language class="ApplicationBlogAdministration" path="/table/action"
                language-de_DE=""
                language-en_US=""}"
                }
                }
            </CMS>
        </div>
        <div class="card-container-footer">
            <div class="flex-container">
                <div class="flex-container-item"
                     style="flex: 2;font-weight: bold;">
                    {insert/language class="ApplicationBlogAdministration" path="/default/legend"
                    language-de_DE="Legende"
                    language-en_US="Legend"}
                </div>
                <div class="flex-container-item">
                    {insert/function function="googlematerialicons" icon="drafts" class="icon-big"}
                    {insert/language class="ApplicationBlogAdministration" path="/default/draft"
                    language-de_DE="Entwurf"
                    language-en_US="Draft"}
                </div>
                <div class="flex-container-item">
                    {insert/function function="googlematerialicons" icon="visibility" class="icon-big"}
                    {insert/language class="ApplicationBlogAdministration" path="/default/show"
                    language-de_DE="Sichtbar"
                    language-en_US="Show"}
                </div>
                <div class="flex-container-item">
                    {insert/function function="googlematerialicons" icon="visibility_off" class="icon-big"}
                    {insert/language class="ApplicationBlogAdministration" path="/default/hide"
                    language-de_DE="Unsichtbar"
                    language-en_US="Hide"}
                </div>
            </div>
        </div>
    </div>
</div>
{create/pagination ident="blog"}
