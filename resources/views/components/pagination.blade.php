<div class="overflow-x-auto">
  <nav class="mt-2 pb-10">
    <ul class="inline-flex -space-x-px mt-2 text-xs">
      @if ($paginator->currentPage() > 1)
        <li>
          <a href="?page={{ $paginator->currentPage() - 1 }}" 
            class="py-2 px-3 ml-0 leading-tight text-gray-500 bg-white 
              rounded-l-lg border border-gray-300 hover:bg-gray-100 
              hover:text-gray-700">
            Anterior
          </a>
        </li>
      @endif
      @for ($i = 1; $i <= $paginator->lastPage(); $i++)
        <li>
          <a href="?page={{ $i }}" 
              class="py-2 px-3 {{ $paginator->currentPage() == $i ? 'text-blue-600 bg-blue-50' : 'text-gray-500 bg-white' }}
              border border-gray-300 hover:bg-gray-100 
              hover:text-gray-700">
              {{ $i }}
          </a>
        </li>
      @endfor
      @if ($paginator->currentPage() < $paginator->lastPage())
        <li>
          <a href="?page={{ $paginator->currentPage() + 1 }}" 
            class="py-2 px-3 leading-tight text-gray-500 bg-white
            rounded-r-lg border border-gray-300 hover:bg-gray-100 
            hover:text-gray-700">
            Próxima
          </a>
        </li>
      @endif
    </ul>
  </nav>
</div>