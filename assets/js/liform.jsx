import React from 'react'
import ReactOnRails from 'react-on-rails'
import { Liform, DefaultTheme, renderField, finalizeName } from 'liform-react-final'
import { Field } from 'react-final-form'

const demoRenderField = (props) => {
    return <div style={{border: "1px solid grey", padding: "5px", margin: "5px"}}>
        <div><small>name: {props.name}</small></div>
        <div><code>schema: { JSON.stringify({widget: props.schema.widget, type: props.schema.type, format: props.schema.format}) }</code></div>
        { props.schema.type &&
            <Field name={finalizeName(props.name)} render={({input: {value}}) => 
                <div><code>value: { JSON.stringify(value) }</code></div>
            }/>
        }
        <div style={{marginTop: "5px"}}>{ renderField(props) }</div>
    </div>
}

const DemoForm = ({verboseFields, ...props}) => {
    const render = verboseFields ? {field: demoRenderField} : undefined

    return (
        <Liform theme={DefaultTheme} render={render} {...props}>
            <header>{ props => <div><code>Initial values: { JSON.stringify(props.initialValues._) }</code></div> }</header>
        </Liform>
    )
}

ReactOnRails.register({ DemoForm })
