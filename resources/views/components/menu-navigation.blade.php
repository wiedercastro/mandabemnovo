<div class="text-xs sm:text-sm font-medium text-center text-gray-500 border-b border-gray-200 mt-6">
  <ul class="flex flex-col sm:flex-row -mb-px">
    <li class="me-2">
      <a href="{{route('acompanhamento')}}" class="{{ request()->routeIs('acompanhamento') ? 'border-blue-600 text-blue-600' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }} inline-block p-4 border-b-2 rounded-t-lg">Acompanhamento</a>
    </li>
    <li class="me-2">
      <a href="#" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300">Manifestação</a>
    </li>
    <li class="me-2">
      <a href="{{route('edicao.email')}}" class="{{ request()->routeIs('edicao.email') ? 'border-blue-600 text-blue-600' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }} inline-block p-4 border-b-2 rounded-t-lg">Edição de E-mails</a>
    </li>
    <li class="me-2">
      <a href="{{route('gerenciamento_crise')}}" class="{{ request()->routeIs('gerenciamento_crise') ? 'border-blue-600 text-blue-600' : 'border-transparent hover:text-gray-600 hover:border-gray-300' }} inline-block p-4 border-b-2 rounded-t-lg">Gerenciamento Crise</a>
    </li>
  </ul>
</div>