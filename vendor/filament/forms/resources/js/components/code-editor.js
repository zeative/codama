import { EditorState, Compartment } from '@codemirror/state'
import { EditorView, basicSetup } from 'codemirror-v6'
import { indentWithTab } from '@codemirror/commands'
import { oneDark } from '@codemirror/theme-one-dark'
import { keymap } from '@codemirror/view'

import { cpp } from '@codemirror/lang-cpp'
import { css } from '@codemirror/lang-css'
import { go } from '@codemirror/lang-go'
import { html } from '@codemirror/lang-html'
import { java } from '@codemirror/lang-java'
import { javascript } from '@codemirror/lang-javascript'
import { json } from '@codemirror/lang-json'
import { markdown } from '@codemirror/lang-markdown'
import { php } from '@codemirror/lang-php'
import { python } from '@codemirror/lang-python'
import { xml } from '@codemirror/lang-xml'
import { yaml } from '@codemirror/lang-yaml'

export default function codeEditorFormComponent({
    isDisabled,
    language,
    state,
}) {
    return {
        editor: null,
        themeCompartment: new Compartment(),
        state,

        init() {
            const languageExtension = this.getLanguageExtension()

            this.editor = new EditorView({
                parent: this.$refs.editor,
                state: EditorState.create({
                    doc: this.state,
                    extensions: [
                        basicSetup,
                        keymap.of([indentWithTab]),
                        EditorState.readOnly.of(isDisabled),
                        EditorView.editable.of(!isDisabled),
                        EditorView.updateListener.of((viewUpdate) => {
                            if (!viewUpdate.docChanged) {
                                return
                            }

                            this.state = viewUpdate.state.doc.toString()
                        }),
                        ...(languageExtension ? [languageExtension] : []),
                        this.themeCompartment.of(this.getThemeExtensions()),
                    ],
                }),
            })

            this.$watch('state', () => {
                if (this.state === undefined) {
                    return
                }

                if (this.editor.state.doc.toString() === this.state) {
                    return
                }

                this.editor.dispatch({
                    changes: {
                        from: 0,
                        to: this.editor.state.doc.length,
                        insert: this.state,
                    },
                })
            })

            this.themeObserver = new MutationObserver(() => {
                this.editor.dispatch({
                    effects: this.themeCompartment.reconfigure(
                        this.getThemeExtensions(),
                    ),
                })
            })

            this.themeObserver.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class'],
            })
        },

        isDarkMode() {
            return document.documentElement.classList.contains('dark')
        },

        getThemeExtensions() {
            return this.isDarkMode() ? [oneDark] : []
        },

        getLanguageExtension() {
            if (!language) {
                return null
            }

            const extensions = {
                cpp,
                css,
                go,
                html,
                java,
                javascript,
                json,
                markdown,
                php,
                python,
                xml,
                yaml,
            }

            return extensions[language]?.() || null
        },

        destroy() {
            if (this.themeObserver) {
                this.themeObserver.disconnect()
                this.themeObserver = null
            }

            if (this.editor) {
                this.editor.destroy()
                this.editor = null
            }
        },
    }
}
