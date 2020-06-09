import React from 'react'
import ReactOnRails from 'react-on-rails'
import Liform from 'liform-react-final'
import { renderField } from 'liform-react-final/dist/field'
import { Field } from 'react-final-form'

const demoRenderField = (props) => {
    return <div style={{border: "1px solid grey", padding: "5px", margin: "5px"}}>
        <div><small>name: {props.name}</small></div>
        <div><code>schema: { JSON.stringify({widget: props.schema.widget, type: props.schema.type, format: props.schema.format}) }</code></div>
        { props.name &&
            <Field name={props.name} render={({input: {value}}) => 
                <div><code>value: { JSON.stringify(value) }</code></div>
            }/>
        }
        <div style={{marginTop: "5px"}}>{ renderField(props) }</div>
    </div>
}

const DemoForm = ({verboseFields, ...props}) => {
    if (verboseFields) {
        props.renderField = demoRenderField
    }

    return <Liform {...props}/>
}

ReactOnRails.register({ DemoForm })
