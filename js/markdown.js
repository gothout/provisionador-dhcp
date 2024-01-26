// Função para converter Markdown para HTML
function converterMarkdownParaHTML(markdownText) {
    return marked.parse(markdownText);
}