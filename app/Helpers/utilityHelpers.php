<?php
if( ! function_exists('pageDetails')) {
    function pageDetails(\Illuminate\Contracts\Pagination\LengthAwarePaginator $paginator) {
        echo "<p>Showing {$paginator->firstItem()} to {$paginator->lastItem()} out of {$paginator->total()}</p>";
    }
}
