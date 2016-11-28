<?php
$presenter = new Illuminate\Pagination\BootstrapPresenter($paginator);
?>

<?php if ($paginator->getLastPage() > 1): ?>
    <nav class="text-right"> 
    
        <ul class="pagination">
            <?php echo getPrevious($paginator->getCurrentPage(), $paginator->getUrl( $paginator->getCurrentPage()-1 ) ) ?>
            <?php echo $presenter->getPageRange(1, $paginator->getLastPage() ); ?>
            <?php echo getNext($paginator->getCurrentPage(), $paginator->getLastPage(), $paginator->getUrl( $paginator->getCurrentPage()+1 ) )  ?>

        </ul>
        
    </nav>
   
<?php endif; ?>

<?php
function getPrevious($currentPage, $url)
{
    if ($currentPage <= 1)
        return '<li class="previous disabled"> <span aria-hidden="true">&laquo;</span></li>';
    else
       return '<li class="previous"><a class="icon-chevron-left" href="'.$url.'"> <span aria-hidden="true">&laquo;</span></a></li>';
}

function getNext($currentPage, $lastPage, $url)
{
    if ($currentPage >= $lastPage)
        return '<li class="next disabled"><span aria-hidden="true">&raquo;</span></li>';
    else
        return '<li class="next"><a  href="'.$url.'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
}
?>




